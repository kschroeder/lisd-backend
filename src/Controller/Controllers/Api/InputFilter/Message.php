<?php

namespace Lisd\Controller\Controllers\Api\InputFilter;

use Lisd\Controller\Controllers\Api\Validator\RoomExists;
use Zend\InputFilter\InputFilter;

class Message extends InputFilter
{

    private $roomExists;

    public function __construct(
        RoomExists $roomExists
    )
    {
        $this->roomExists = $roomExists;
    }

    public function init()
    {
        $this->add([
            'name' => 'message',
            'required' => true,
            'validators' => [
                [
                    'name' => 'stringLength',
                    'options' => [
                        'min' => 3,
                        'max' => 255
                    ]
                ]
            ]
        ]);
        $this->add([
            'name' => 'room',
            'required' => true,
            'validators' => [
                $this->roomExists
            ]
        ]);

    }

}
