<?php

namespace Lisd\Repositories;

use Application\Application;
use Litipk\Jiffy\UniversalTimestamp;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;
use Psr\Log\LoggerInterface;

abstract class AbstractRepository
{
    private $mongo;
    private $logger;

    private static $databaseName;

    public function __construct(
        Client $mongo,
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
        $this->mongo = $mongo;
    }

    abstract public function getCollectionName();

    public static function setDatabaseName($dbName)
    {
        self::$databaseName = $dbName;
    }

    public function getConnection()
    {
        return $this->mongo;
    }

    public function getDatabase()
    {
        return $this->getConnection()->selectDatabase(self::$databaseName);
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function load($query, array $sort = null, $limit = PHP_INT_MAX, $skip = 0)
    {
        $extra = $query;
        $this->logger->info('Loading from ' . $this->getCollectionName(), $extra);
        $query['object_status'] = 'active';
        $options = $this->getOptions();
        $options['limit'] = $limit;
        $options['skip'] = $skip;
        if ($sort) {
            $options['sort'] = $sort;
        }
        return $this->getCollection()->find($query, $options);
    }

    public function getOptions()
    {
        return [];
    }

    public function getCollection()
    {
        return $this->mongo->selectCollection(self::$databaseName, $this->getCollectionName());
    }

    /**
     * @param $id
     * @return AbstractDocument
     */

    public function loadById($id)
    {
        if (!$id instanceof ObjectID) {
            $id = new ObjectID($id);
        }
        $results = $this->load(['_id' => $id])->toArray();
        return array_shift($results);
    }

    public function save(AbstractDocument $document, $id = null)
    {
        if (!isset($document['object_status'])) {
            $document['object_status'] = 'active';
        }

        if ($document->getId()) {
            $id = $document->getId();
        }
        if ($id !== null && !$id instanceof ObjectID) {
            $id = new ObjectID($id);
        }

        $document['modified_at'] = self::getTimestamp();
        if ($id instanceof ObjectID) {
            $result = $this->getCollection()->replaceOne(['_id' => $id], $document, $this->getOptions());
        } else {
            $document['created_at'] = self::getTimestamp();
            $result = $this->getCollection()->insertOne($document, $this->getOptions());
        }
        return $result;
    }

    public static function getTimestamp()
    {
        return new UTCDateTime(UniversalTimestamp::now()->asMilliseconds());
    }

    public function delete(AbstractDocument $document)
    {
        $document['object_status'] = 'deleted';
        $this->save($document, $document->getId());
    }

}
