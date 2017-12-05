<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Magium\Configuration\Config\Repository\ConfigInterface;
use Magium\ConfigurationManager\Pusher\PusherFactory;
use Magium\ConfigurationManager\Pusher\PusherViewHelper;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Pusher extends AbstractController
{

    private $helper;
    private $config;

    public function __construct(
        PusherViewHelper $helper,
        ConfigInterface $config
    )
    {
        $this->helper = $helper;
        $this->config = $config;
    }

    public function execute(): ResponseInterface
    {
        $options = [
            'cluster' => $this->config->getValue(PusherFactory::CONFIG_CLUSTER)
        ];
        $result = $this->helper->getConnectionJavaScript($options);
        $output = <<<HTML
    <script type="text/javascript">
        var pusher = {$result}
    </script>
HTML;
        return new HtmlResponse($output);
    }

}
