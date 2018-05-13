<?php

namespace WonderKind\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WonderKind\Service\ReportingService;


/**
 * Class CountReTweeterFollowersCommand
 * @package WonderKind\Command
 */
class CountReTweeterFollowersCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'wonderkind:count-retweeter-followers';
    /**
     * @var ReportingService
     */
    private $reportService;


    /**
     * CountReTweeterFollowersCommand constructor.
     * @param ReportingService $reportService
     */
    public function __construct(ReportingService $reportService)
    {
        parent::__construct();
        $this->reportService = $reportService;
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Count the amount of followers each user has that has re-tweeted a tweet.')
            ->setHelp($this->getCommandHelp())
            ->addOption('tweet-url', 't', InputOption::VALUE_REQUIRED, 'Enter the tweet URL');
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     * @throws \WonderKind\Exception\TwitterException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getOption('tweet-url');
        if (empty($url)) {
            throw new \InvalidArgumentException("Invalid URL Specified.");
        }
        $totalFollowers = $this->reportService->CountReTweeterFollowers($url);
        $output->writeln("Found total of {$totalFollowers} followers to users who retweeted {$url}");
    }

    /**
     * @return string
     */
    private function getCommandHelp()
    {
        return <<<'HELP'
The <info>%command.name%</info> counts the amount of followers each user has that has re-tweeted a tweet.
  Use <comment>--tweet-url</comment> option ex:
        <info>php %command.full_name%</info> <comment>--tweet-url=https://twitter.com/realDonaldTrump/status/995044981768425477</comment>
HELP;
    }
}