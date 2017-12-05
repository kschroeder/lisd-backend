<?php

namespace Lisd\Repositories\Message;

use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Room\Room;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Model\BSONArray;

class MessageRepository extends AbstractRepository
{
    public function getCollectionName()
    {
        return 'messages';
    }

    /**
     * @param Room $room
     * @param int $createdAt
     * @return Message[]
     */

    public function loadByRoomAndCreatedAt(Room $room, int $createdAt): ?array
    {
        $result = $this->load([
            'room_id' => $room->getId(),
            'created_at' => new UTCDateTime($createdAt)
        ])->toArray();
        return $result;
    }

    /**
     * @param Room[] $rooms
     * @return Message[]
     */

    public function loadByRooms(array $rooms): ?array
    {
        $allRooms = [];
        foreach ($rooms as $room) {
            if ($room instanceof Room) {
                $allRooms[] = $room->getId();
            }
        }

        $result = $this->load([
            'room_id' => [
                '$in' => $allRooms
            ]
        ], [
            'created_at' => -1
        ])->toArray();
        return $result;
    }

    public function getOptions()
    {
        return [
            'typeMap' => [
                'array' => BSONArray::class,
                'document' => Message::class,
                'root' => Message::class,
            ]
        ];
    }

}
