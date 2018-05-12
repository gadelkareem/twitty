<?php

namespace WonderKind\Service;

use Psr\Log\LoggerInterface;
use WonderKind\Twitter\TwitterClient;
use Symfony\Component\Cache\Adapter\AdapterInterface;

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
    /*
     * @var AdapterInterface
     */
    private $cache;

    /**
     * TwitterClient constructor.
     * @param TwitterClient $client
     * @param AdapterInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(TwitterClient $client, AdapterInterface $cache, LoggerInterface $logger)
    {
        $this->twitter = $client;
        $this->logger = $logger;
        $this->cache = $cache;
    }


    /**
     * @param string $url
     * @return int
     * @throws \Exception
     * @throws \WonderKind\Exception\TwitterException
     */
    public function CountReTweeterFollowers(string $url): int
    {
        $cacheKey = "CountReTweeterFollowers_" . md5($url);
        $cacheItem = $this->cache->getItem($cacheKey);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $id = $this->twitter->extractTweetIdFromUrl($url);
        $reTweeters = $this->twitter->getReTweeters($id);
        $totalFollowers = $this->twitter->calculateRetweetFollowers($reTweeters);

        $cacheItem->set($totalFollowers)->expiresAfter(new \DateInterval("PT2H"));
        $this->cache->save($cacheItem);

        return $totalFollowers;
    }
}
