<?php

namespace Lisd\Processing\Inline;

use GuzzleHttp\Client;
use Lisd\Repositories\Character\Character;
use Lisd\Repositories\Character\CharacterRepository;
use Magium\Configuration\Config\Repository\ConfigInterface;

class Characters
{
    const CONFIG_API_KEY = 'marvel/api/key';
    const CONFIG_API_SECRET = 'marvel/api/secret';

    private $config;
    private $charactersRepository;

    public function __construct(
        ConfigInterface $config,
        CharacterRepository $characterRepository
    )
    {
        $this->config = $config;
        $this->charactersRepository = $characterRepository;
    }

    public function execute()
    {
        $this->charactersRepository->getCollection()->deleteMany([]);

        $offset = 0;
        do {
            $ts = uniqid();
            $hash = md5($ts . $this->config->getValue(self::CONFIG_API_SECRET) . $this->config->getValue(self::CONFIG_API_KEY));
            $params = [
                'offset' => $offset,
                'ts' => $ts,
                'apikey' => $this->config->getValue(self::CONFIG_API_KEY),
                'hash' => $hash,
                'limit' => 100
            ];

            $queryString = http_build_query($params);

            $uri = 'https://gateway.marvel.com:443/v1/public/characters?' . $queryString;
            $guzzle = new Client();
            $body = $guzzle->get($uri)->getBody();
            $body->rewind();
            $response = json_decode($body->getContents(), true);
            if (isset($response['data']['results'])) {
                foreach ($response['data']['results'] as $character) {
                    $instance = new Character();
                    $instance->setName($character['name']);
                    $instance->setDescription($character['description']);
                    $instance->setImage($character['thumbnail']['path'] . '.' . $character['thumbnail']['extension']);
                    $this->charactersRepository->save($instance);
                }
            }
            $offset += 100;
        } while ($offset < $response['data']['total']);

    }

}
