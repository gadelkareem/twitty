<?php

namespace WonderKind\Service;

use Psr\Log\LoggerInterface;
use WonderKind\Twitter\TwitterClient;


/**
 * Class ReportService
 * @package WonderKind\Twitter
 */
class ReportService
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var TwitterClient
     */
    private $twitter;

    /**
     * TwitterClient constructor.
     * @param TwitterClient $client
     * @param LoggerInterface $logger
     */
    public function __construct(TwitterClient $client, LoggerInterface $logger)
    {
        $this->twitter = $client;
        $this->logger = $logger;
    }


    /**
     * @param string $url
     * @return int
     * @throws \WonderKind\Exception\TwitterException
     */
    public function CountReTweeterFollowers(string $url): int
    {
        $id = $this->twitter->extractTweetIdFromUrl($url);
        $reTweeters = $this->twitter->getReTweeters($id);
        return $this->twitter->calculateRetweetFollowers($reTweeters);
    }
}
