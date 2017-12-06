<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractUnauthenticatedController;
use Magium\Configuration\Config\Repository\ConfigInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Analytics extends AbstractUnauthenticatedController
{
    const CONFIG_TRACKING_KEY = 'google/analytics/tracking_id';

    private $config;

    public function __construct(
        ConfigInterface $config
    )
    {
        $this->config = $config;
    }

    public function execute(): ResponseInterface
    {
        $key = $this->config->getValue(self::CONFIG_TRACKING_KEY);
        $response = <<<HTML
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={$key}"></script>
<script>
    window.gaTrackingId = '{$key}';
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{$key}');
</script>
HTML;
        return new HtmlResponse($response);
    }

}
