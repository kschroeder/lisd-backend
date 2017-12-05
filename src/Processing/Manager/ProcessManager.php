<?php

namespace Lisd\Processing\Manager;

use Aws\Sqs\SqsClient;
use Lisd\Processing\Processor\ProcessorInterface;
use Magium\AwsFactory\AwsFactory;
use Psr\Log\LoggerInterface;
use Zend\Di\Di;

class ProcessManager
{

    private $logger;
    private $di;

    /**
     * @var SqsClient
     */
    private $sqs;

    public function __construct(
        LoggerInterface $logger,
        AwsFactory $awsFactory,
        Di $di
    )
    {
        $this->logger = $logger;
        $this->di = $di;
//        $this->sqs = $awsFactory->factory(SqsClient::class);
    }

    public function execute(array $payload)
    {
        $type = $payload['type']??null;
        $id = $payload['id']??null;
        if (!$type || !$id) {
            $this->logger->error('Missing ID or type in process payload', $payload);
            return;
        }
        $instance = $this->di->newInstance($type);
        if ($instance instanceof ProcessorInterface) {
            $instance->execute($id);
        }
    }

    public function queue(ProcessorInterface $processor)
    {
        $payload = [
            'type' => get_class($processor),
            'id' => $processor->getTarget()->getId()
        ];
        $payload = json_encode($payload);
        return;
        $this->sqs->sendMessage([
            'MessageBody' => $payload
        ]);
    }

}
