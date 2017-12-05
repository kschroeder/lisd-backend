<?php

namespace Lisd\Processing\Manager;

use Aws\Sqs\SqsClient;
use Lisd\Processing\Processor\ProcessorInterface;
use Magium\AwsFactory\AwsFactory;
use Magium\Configuration\Config\Repository\ConfigInterface;
use Psr\Log\LoggerInterface;
use Zend\Di\Di;

class ProcessManager
{
    const CONFIG_ENDPOINT = 'process_queue/sqs/endpoint';

    private $logger;
    private $di;
    private $config;

    /**
     * @var SqsClient
     */
    private $sqs;

    public function __construct(
        LoggerInterface $logger,
        AwsFactory $awsFactory,
        ConfigInterface $config,
        Di $di
    )
    {
        $this->logger = $logger;
        $this->di = $di;
        $this->sqs = $awsFactory->factory(SqsClient::class);
        $this->config = $config;
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
            'id' => (string)$processor->getTarget()->getId()
        ];
        $payload = json_encode($payload);

        $this->sqs->sendMessage([
            'QueueUrl' => $this->config->getValue(self::CONFIG_ENDPOINT),
            'MessageBody' => $payload
        ]);
    }

}
