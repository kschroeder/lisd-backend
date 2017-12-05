<?php

namespace Lisd\Repositories\Character;

use Lisd\Repositories\AbstractRepository;
use MongoDB\Model\BSONArray;

class CharacterRepository extends AbstractRepository
{
    public function getCollectionName()
    {
        return 'characters';
    }

    public function getOptions()
    {
        return [
            'typeMap' => [
                'array' => BSONArray::class,
                'document' => Character::class,
                'root' => Character::class,
            ]
        ];
    }

}
