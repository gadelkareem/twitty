<?php

namespace WonderKind\Tests\Mock;

use WonderKind\Twitter\TwitterClient;
use \Abraham\TwitterOAuth\Util\JsonDecoder;

class TwitterClientMock extends TwitterClient
{
    private $data = [];

    /**
     * TwitterClientMock constructor.
     */
    public function __construct()
    {
    }

    public function getReTweeters(string $id): array
    {
        if (!isset($this->data['status_retweets_id'])) {
            throw new \BadMethodCallException("'status_retweets_id' data missing.");
        }
        return JsonDecoder::decode($this->data['status_retweets_id'], false);
    }


    public function addData(string $key, string $value)
    {
        $this->data[$key] = $value;
    }


}
