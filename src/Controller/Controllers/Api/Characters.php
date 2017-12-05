<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\RequestToJson;
use Lisd\Repositories\Character\Character;
use Lisd\Repositories\Character\CharacterRepository;
use Lisd\View\Responses\SuccessfulApiResponse;
use MongoDB\BSON\Regex;
use Psr\Http\Message\ResponseInterface;

class Characters extends AbstractController
{
    private $request;
    private $characterRepository;

    public function __construct(
        RequestToJson $request,
        CharacterRepository $characterRepository
    )
    {
        $this->request = $request;
        $this->characterRepository = $characterRepository;
    }

    public function execute(): ResponseInterface
    {
        $data = $this->request->json();
        $query = [];
        if (isset($data['search'])) {
            $regex = new Regex('.*' . $data['search'] . '.*', 'i');
            $query = [
                '$or' => [
                    [
                        'name' => $regex
                    ],
                    [
                        'description' => $regex
                    ],
                ]
            ];
        }
        $results = $this->characterRepository->load($query, null, 9);
        $return = [];
        foreach ($results as $result) {
            /** @var $result Character */
            $return[] = [
                'name' => $result->getName(),
                'description' => $result->getDescription(),
                'image' => $result->getImage(),
            ];
        }
        return (new SuccessfulApiResponse())->getResponse($return);
    }

}
