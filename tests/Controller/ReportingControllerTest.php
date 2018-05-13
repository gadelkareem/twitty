<?php

namespace WonderKind\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use WonderKind\Tests\Mock\TwitterClientMock;

class ReportingControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient([]);
        /** @var TwitterClientMock $twitterClientMock */
        $twitterClientMock = $client->getContainer()->get(TwitterClientMock::class);
        $twitterClientMock->addData("status_retweets_id", file_get_contents(realpath(self::$kernel->getRootDir() . "/../tests/data/status_retweets_id.json")));

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('form[submit]')->form([
            'form[url]' => "https://twitter.com/SpaceX/status/995043176363671552",
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'Found total of 6999316 followers to users who retweeted https://twitter.com/SpaceX/status/995043176363671552',
            $client->getResponse()->getContent()
        );
    }

}

