<?php

namespace Lisd\Processing\InboundAdapter;

use Aws\Sqs\SqsClient;
use Lisd\Processing\Manager\ProcessManager;
use Magium\AwsFactory\AwsFactory;
use Magium\Configuration\Config\Repository\ConfigInterface;

class Sqs
{
    const SETTINGS_QUEUE = 'process_queue/sqs/endpoint';

    private $factory;
    private $processManager;
    private $config;

    public function __construct(
        AwsFactory $factory,
        ProcessManager $processManager,
        ConfigInterface $config
    )
    {
        $this->factory = $factory;
        $this->processManager = $processManager;
        $this->config = $config;
    }

    public function execute()
    {
        $sqs = $this->factory->factory(SqsClient::class);
        if ($sqs instanceof SqsClient) {
            while (true) {
                try {
                    $messages = $sqs->receiveMessage([
                        'QueueUrl' => $this->config->getValue(self::SETTINGS_QUEUE)
                    ]);
                    if ($messages) {
                        $allMessages = $messages->get('Messages');
                        foreach ($allMessages as $message) {
                            $body = $message['Body'];
                            if ($body) {
                                $payload = json_decode($body, true);
                                $this->processManager->execute($payload);
                            }
                        }
                }
                } catch (\Exception $e) {

                }
            }
        }
    }

}
