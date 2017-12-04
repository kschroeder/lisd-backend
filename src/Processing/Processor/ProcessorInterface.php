<?php

namespace Lisd\Processing\Processor;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\AbstractRepository;

interface ProcessorInterface
{

    public function execute($id);

    public function getTarget(): AbstractDocument;

    public function getTargetRepository(): AbstractRepository;

}
