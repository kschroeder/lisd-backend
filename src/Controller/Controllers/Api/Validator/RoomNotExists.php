<?php

namespace Lisd\Controller\Controllers\Api\Validator;

use Lisd\Repositories\Room\Room;
use Lisd\Repositories\Room\RoomRepository;
use Zend\Validator\AbstractValidator;

class RoomNotExists extends AbstractValidator
{
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }


    public function isValid($value)
    {
        $room = $this->roomRepository->loadByName($value);
        return  !$room instanceof Room;
    }

}
