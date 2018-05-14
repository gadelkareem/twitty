<?php
declare(strict_types=1);

namespace WonderKind\Tests\Mock;

use WonderKind\Twitter\TwitterClient;
use \Abraham\TwitterOAuth\Util\JsonDecoder;

/**
 * Class TwitterClientMock
 * @package WonderKind\Tests\Mock
 */
class TwitterClientMock extends TwitterClient
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * TwitterClientMock constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param int $id
     * @return array
     */
    public function getReTweeters(int $id): array
    {
        if (!isset($this->data['status_retweets_id'])) {
            throw new \BadMethodCallException("'status_retweets_id' data missing.");
        }
        return JsonDecoder::decode($this->data['status_retweets_id'], false);
    }


    /**
     * @param string $key
     * @param string $value
     */
    public function addData(string $key, string $value)
    {
        $this->data[$key] = $value;
    }


}
