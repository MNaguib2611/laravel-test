<?php

namespace App\Services;

use App\Exceptions\UnsuccessfulTextModerationRequest;
use Http;


//A service that uses a third party API to verify text doent have and profanities
class TextModerator
{
    protected $baseUrl;
    protected $apiUser;
    protected $apiSecret;
    //   ATTENTION
    //I changed the constructor since $baseUrl -> this is constant //"https://api.sightengine.com/1.0/text/check.json"
    //                                $apiUser -> this is constant  from app environment
    //                                $apiSecret -> this is constant  from app environment
    public function __construct()
    {
        $this->baseUrl   = "https://api.sightengine.com/1.0/text/check.json";
        $this->apiUser   =  env('SIGHT_ENGINE_API_USER',null);
        $this->apiSecret = env('SIGHT_ENGINE_API_SECRET',null);
    }

    public function check(string $text): bool
    {
        $response = Http::asForm()->post($this->baseUrl, $this->data($text));

        $body = json_decode($response->body(), true);

        if ((int) $response->status() === 200) {
            return count($body['profanity']['matches']) === 0;
        }

        throw new UnsuccessfulTextModerationRequest($body['error']['message']);
    }

    private function data(string $text): array
    {
        return [
            'text' => $text,
            'lang' => 'en',
            'mode' => 'standard',
            'api_user' => $this->apiUser,
            'api_secret' => $this->apiSecret,
        ];
    }
}
