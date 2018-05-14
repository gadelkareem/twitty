<?php

namespace WonderKind\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Psr\Log\LoggerInterface;
use WonderKind\Exception\TwitterException;


class TwitterClient
{
    /**
     * @var TwitterOAuth
     */
    private $client;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * TwitterClient constructor.
     * @param TwitterOAuth $client
     * @param LoggerInterface $logger
     */
    public function __construct(TwitterOAuth $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param string $url
     * @return int
     * @throws \InvalidArgumentException
     */
    public function extractTweetIdFromUrl(string $url): int
    {
        //ex: https://twitter.com/SpaceX/status/995043176363671552
        $id = (int)preg_replace("#^https?:\/\/twitter\.com\/[^/]+\/status\/([0-9]+)/?$#", "$1", $url);
        if (empty($id)) {
            throw new \InvalidArgumentException("Invalid URL specified {$url} id: {$id}");
        }
        return $id;
    }

    /**
     * GET statuses/retweets/:id
     * Returns a collection of the 100 most recent retweets of the Tweet specified by the id parameter.
     * uri: https://api.twitter.com/1.1/statuses/retweets/:id.json
     * Rate limited?    Yes
     * Requests / 15-min window (user auth)    75
     * Requests / 15-min window (app auth)    300
     *
     * @link: https://developer.twitter.com/en/docs/tweets/post-and-engage/api-reference/get-statuses-retweets-id
     * @param string $id
     * @return array
     * @throws TwitterException
     */
    public function getReTweeters(string $id): array
    {
        $this->logger->debug("New getReTweeters request for id {$id}");
        $retweets = $this->client->get("statuses/retweets/{$id}", ["count" => 100]);

        $this->errorHandler($retweets);

        return $retweets;
    }

    /**
     * Calculate retweeted users' number of followers.
     * @param array $retweets
     * @return int
     */
    public function calculateRetweetFollowers(array $retweets): int
    {
        $total = 0;
        $userIds = [];
        foreach ($retweets as $retweet) {
            if (!isset($userIds[$retweet->user->id])) {
                $total += $retweet->user->followers_count;
                $userIds[$retweet->user->id] = true;
            }
            if (!isset($userIds[$retweet->retweeted_status->user->id])) {
                $total += $retweet->retweeted_status->user->followers_count;
                $userIds[$retweet->retweeted_status->user->id] = true;
            }

        }
        return $total;
    }

    /**
     * Error Handler.
     * @param array|object $response
     * @throws TwitterException
     */
    private function errorHandler($response)
    {
        if (is_object($response) && property_exists($response, "errors")) {
            $message = "";
            foreach ($response->errors as $error) {
                $message .= "[Code {$error->code}] $error->message, ";
            }
            $this->logger->critical("Twitter Client error", ["cause" => $message]);
            throw new TwitterException($message);
        }
    }
}
