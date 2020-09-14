<?php

namespace App\Services;

use App\Exceptions\UnsuccessfulTextModerationRequest;
use Http;

class TextModerator
{
    protected $baseUrl;
    protected $apiUser;
    protected $apiSecret;

    public function __construct(string $baseUrl, string $apiUser, string $apiSecret)
    {
        $this->baseUrl = $baseUrl;
        $this->apiUser = $apiUser;
        $this->apiSecret = $apiSecret;
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
