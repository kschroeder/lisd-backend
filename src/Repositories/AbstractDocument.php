<?php

namespace Lisd\Repositories;

use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Model\BSONDocument;

abstract class AbstractDocument extends BSONDocument
{

    /**
     * @return ObjectID
     */
    public function getId()
    {
        if (!isset($this['_id'])) {
            return null;
        }
        return $this['_id'];
    }

    public function setId(ObjectID $objectID)
    {
        $this['_id'] = $objectID;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt(UTCDateTime $created_at)
    {
        $this['created_at'] = $created_at;
    }

    /**
     * @param mixed $modified_at
     */
    public function setModifiedAt(UTCDateTime $modified_at)
    {
        $this['modified_at'] = $modified_at;
    }

    /**
     * @return UTCDateTime
     */
    public function getCreatedAt()
    {
        return $this['created_at'];
    }

    /**
     * @return UTCDateTime
     */
    public function getModifiedAt()
    {
        return $this['modified_at'];
    }

    public function setData($data)
    {
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }
    }
}
