<?php

namespace Lisd\Controller\Controllers\Api\InputFilter;

use Lisd\Controller\Controllers\Api\Validator\RoomExists;
use Lisd\Repositories\Room\Room;
use Zend\InputFilter\InputFilter;

class GetMessages extends InputFilter
{

    private $roomExists;

    public function __construct(
        RoomExists $roomExists
    )
    {
        $this->roomExists = $roomExists;
    }

    public function getRoom(): ?Room
    {
        return $this->roomExists->getRoom();
    }

    public function init()
    {
        $this->add([
            'name' => 'room',
            'required' => true,
            'validators' => [
                $this->roomExists
            ]
        ]);
        $this->add([
            'name' => 'since',
            'required' => true,
            'validators' => [
                [
                    'name' => 'Digits'
                ]
            ]
        ]);

    }

}
