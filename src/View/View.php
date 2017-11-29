<?php

namespace Lisd\View;

use Zend\Diactoros\Response\HtmlResponse;

class View
{
    private $viewFile;
    private $layout;
    private $params;

    public function __construct($viewName = '', array $params = array(), $layoutName = null)
    {
        $this->params = $params;
        $baseFile = __DIR__ .'/../../view/' . $viewName . '.phtml';

        $this->viewFile = realpath($baseFile);
        $this->layout = $layoutName;
    }

    public function getResponse()
    {
        $params = $this->params;
        extract($params);
        ob_start();
        include $this->viewFile;
        $contents = ob_get_clean();
        if ($this->layout) {
            $params = $this->params + ['content' => $contents];
            $view = new View($this->layout, $params);
            $contents = $view->getResponse()->getBody()->getContents();
        }
        return new HtmlResponse($contents);
    }

}
