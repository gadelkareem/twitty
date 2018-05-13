<?php

namespace WonderKind\Service;

use Psr\Log\LoggerInterface;
use WonderKind\Twitter\TwitterClient;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Class ReportingService
 * @package WonderKind\Twitter
 */
class ReportingService
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
        $id = $this->twitter->extractTweetIdFromUrl($url);
        $cacheItem = $this->cache->getItem("CountReTweeterFollowers_" . $id);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        $reTweeters = $this->twitter->getReTweeters($id);
        $totalFollowers = $this->twitter->calculateRetweetFollowers($reTweeters);
        $cacheItem
            ->set($totalFollowers)
            ->expiresAfter(new \DateInterval("PT2H"))
            ->tag([(string)$id]);
        $this->cache->save($cacheItem);
        return $totalFollowers;
    }
}
