<?php

namespace Lisd\Controller\Controllers\Api\Validator;

use Lisd\Repositories\Room\Room;
use Lisd\Repositories\Room\RoomRepository;
use Zend\Validator\AbstractValidator;

class RoomExists extends AbstractValidator
{
    private $roomRepository;
    private $room;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function isValid($value)
    {
        $this->room = $this->roomRepository->loadById($value);
        return  $this->room instanceof Room;
    }

}
