<?php
declare(strict_types=1);

namespace WonderKind\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use WonderKind\Command\CountReTweeterFollowersCommand;
use WonderKind\Service\ReportingService;
use WonderKind\Tests\Mock\TwitterClientMock;

/**
 * Class CountReTweeterFollowersCommandTest
 * @package WonderKind\Tests\Command
 */
class CountReTweeterFollowersCommandTest extends KernelTestCase
{

    /**
     * Testing the count retweets' followers command
     * The test config provide us with an autowired mock service for the twitter client so we only inject the fake response here
     */
    public function testCountReTweeterFollowersCommand()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        /** @var TwitterClientMock $twitterClientMock */
        $twitterClientMock = $container->get(TwitterClientMock::class);
        $twitterClientMock->addData("status_retweets_id", file_get_contents(realpath(self::$kernel->getRootDir() . "/../tests/data/status_retweets_id.json")));

        $command = new CountReTweeterFollowersCommand($container->get(ReportingService::class));
        $command->setApplication(new Application(self::$kernel));

        $commandTester = new CommandTester($command);
        $commandTester->execute(["--tweet-url" => "https://twitter.com/SpaceX/status/995043176363671552"]);

        $this->assertSame("Found total of 6999316 followers to users who retweeted https://twitter.com/SpaceX/status/995043176363671552\n", $commandTester->getDisplay());
    }

}
