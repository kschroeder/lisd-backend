<?php

namespace Lisd\Cron;

use Lisd\Controller\Type\CollectionRepository;
use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Ingest\PHPUnit\PHPUnitRepository;
use Lisd\Repositories\Project\Project;
use Lisd\Repositories\Project\ProjectRepository;
use Lisd\Repositories\TestRun\TestRun;
use Lisd\Repositories\TestRun\TestRunRepository;
use MongoDB\Model\BSONDocument;

class ProcessNewTests implements CronInterface
{
    private $collectionRepository;
    private $testRunRepository;
    private $projectRepository;
    private $projects = [];

    public function __construct(
        CollectionRepository $collectionRepository,
        TestRunRepository $testRunRepository,
        ProjectRepository $projectRepository
    )
    {
        $this->collectionRepository = $collectionRepository;
        $this->testRunRepository = $testRunRepository;
        $this->projectRepository = $projectRepository;
    }

    public function execute()
    {
        $testRuns = $this->executeTestRunMatch();
        $this->setTestRunStatuses($testRuns);
    }

    private function setTestRunStatuses(array $testRuns)
    {
        foreach ($testRuns as $testRun) {
            $testRun = $this->testRunRepository->loadById($testRun);
            if ($testRun instanceof TestRun) {
                $project = $this->getProject($testRun->getProjectId());
                $repository = $this->collectionRepository->get($project->getType());
                $tests = $repository->loadByIds($testRun->getTestIds());
                $status = 'success';
                foreach ($tests as $test) {
                    if (in_array($test->getTestResult(), ['error', 'failed'])) {
                        $status = 'failed';
                        break;
                    }
                }
                $testRun->setTestState($status);
                $this->testRunRepository->save($testRun);
            }
        }
    }

    private function executeTestRunMatch()
    {
        $testRuns = [];
        $repos = $this->collectionRepository->getAllRepositories();
        foreach ($repos as $repo) {
            $results = $this->matchTestRuns($repo);
            foreach ($results as $result) {
                $testRuns[] = $result;
            }
        }
        return $testRuns;
    }

    private function matchTestRuns(AbstractRepository $repository): array
    {
        /**
         * We directly call the collection and not the repository so we can work with the cursor instead of a
         * potentially large array of objects
         */
        $tests = $repository->getCollection()->find([
            'test_run_processed' => ['$exists' => false]
        ]);
        $testRuns = [];
        foreach ($tests as $test) {
            /** @var $test  BSONDocument */
            if (!$test['test_run_id']) continue; // Client didn't send the test run ID
            $testRun = $this->testRunRepository->loadByTestRunId($test['test_run_id']);
            if (!$testRun) {
                $testRun = new TestRun();
                $testRun->setTestRunId($test['test_run_id']);
                $testRun->setProjectId($test['project_id']);
                $project = $this->getProject($test['project_id']);
                $testRun->setTestType($project->getType());
                $objectId = $this->testRunRepository->save($testRun)->getInsertedId();
                $testRun = $this->testRunRepository->loadById($objectId);
            }
            $testRun->addTestId($test['_id']);
            $this->testRunRepository->save($testRun);
            $testRuns[] = $testRun->getId();
            $test['test_run_processed'] = true;
            $repository->getCollection()->replaceOne(['_id' => $test['_id']], $test);
        }
        return $testRuns;
    }

    private function getProject($projectId): Project
    {
        $key = (string)$projectId;
        if (!isset($this->projects[$key])) {
            $this->projects[$key] = $this->projectRepository->loadById($projectId);
        }
        return $this->projects[$key];

    }

}
