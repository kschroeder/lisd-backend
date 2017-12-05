<?php

namespace Lisd\Controller\Controllers\Api\InputFilter;

use Lisd\Controller\Controllers\Api\Validator\RoomExists;
use Lisd\Controller\Controllers\Api\Validator\RoomNotExists;
use Zend\InputFilter\InputFilter;

class CreateRoom extends InputFilter
{

    private $roomNotExists;

    public function __construct(
        RoomNotExists $roomNotExists
    )
    {
        $this->roomNotExists = $roomNotExists;
    }

    public function init()
    {
        $this->add([
            'name' => 'name',
            'required' => true,
            'validators' => [
                [
                    'name' => 'stringLength',
                    'options' => [
                        'min' => 3,
                        'max' => 255
                    ]
                ],
                $this->roomNotExists
            ]
        ]);

    }

}
