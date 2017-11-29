<?php

namespace Lisd\Controller\Type;

use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Ingest\Http\HttpRepository;
use Lisd\Repositories\Ingest\Magium\MagiumRepository;
use Lisd\Repositories\Ingest\PHPUnit\PHPUnitRepository;

class CollectionRepository
{
    const TYPE_MAGIUM = 'magium';
    const TYPE_HTTP = 'http';
    const TYPE_PHPUNIT = 'phpunit';

    private $http;
    private $magium;
    private $phpunit;

    public function __construct(
        HttpRepository $httpRepository,
        MagiumRepository $magiumRepository,
        PHPUnitRepository $PHPUnitRepository
    )
    {
        $this->http = $httpRepository;
        $this->magium = $magiumRepository;
        $this->phpunit = $PHPUnitRepository;
    }

    public function get($type)
    {
        switch ($type) {
            case self::TYPE_PHPUNIT:
                return $this->phpunit;
                break;
            case self::TYPE_MAGIUM:
                return $this->magium;
                break;
            case self::TYPE_HTTP:
                return $this->http;
                break;
        }
        return null;
    }

    /**
     * @return AbstractRepository[]
     */

    public function getAllRepositories()
    {
        return [
            $this->phpunit,
            $this->http,
            $this->magium,
        ];
    }

}
