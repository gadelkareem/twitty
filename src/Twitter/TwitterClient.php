<?php

namespace WonderKind\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterClient
{
    private $client;

    public function __construct(TwitterOAuth $client)
    {
        $this->client = $client;
    }


}
