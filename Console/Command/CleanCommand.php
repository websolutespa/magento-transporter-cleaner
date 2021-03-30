<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCleaner\Console\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Websolute\TransporterBase\Api\TransporterListInterface;
use Websolute\TransporterBase\Logger\Handler\Console;
use Websolute\TransporterCleaner\Model\DeleteActivity;
use Websolute\TransporterCleaner\Model\DeleteAllActivity;

class CleanCommand extends Command
{
    const TYPE = 'type';
    const ALL = 'all';

    /**
     * @var Console
     */
    private $consoleLogger;

    /**
     * @var TransporterListInterface
     */
    private $transporterList;

    /**
     * @var DeleteActivity
     */
    private $deleteActivity;

    /**
     * @var DeleteAllActivity
     */
    private $deleteAllActivity;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @param Console $consoleLogger
     * @param TransporterListInterface $transporterList
     * @param DeleteActivity $deleteActivity
     * @param DeleteAllActivity $deleteAllActivity
     * @param null $name
     */
    public function __construct(
        Console $consoleLogger,
        TransporterListInterface $transporterList,
        DeleteActivity $deleteActivity,
        DeleteAllActivity $deleteAllActivity,
        $name = null
    ) {
        parent::__construct($name);
        $this->consoleLogger = $consoleLogger;
        $this->transporterList = $transporterList;
        $this->deleteActivity = $deleteActivity;
        $this->deleteAllActivity = $deleteAllActivity;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        $text = [];
        $text[] = __('Available DownloaderList types: ')->getText();
        $allDownlaoderList = $this->transporterList->getAllDownloaderList();
        foreach ($allDownlaoderList as $name => $downlaoderList) {
            $text[] = $name;
            $text[] = ', ';
        }
        $text[] = __('Available ManipulatorList types: ')->getText();
        $allManipulatorList = $this->transporterList->getAllManipulatorList();
        foreach ($allManipulatorList as $name => $manipulatorList) {
            $text[] = $name;
            $text[] = ', ';
        }
        $text[] = __('Available UploaderList types: ')->getText();
        $allUplaoderList = $this->transporterList->getAllUploaderList();
        foreach ($allUplaoderList as $name => $uplaoderList) {
            $text[] = $name;
            $text[] = ', ';
        }
        array_pop($text);
        return implode('', $text);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription('Transporter: Delete uploaded Activity, all and/or for a specific Type');

        $this->addArgument(
            self::TYPE,
            InputArgument::OPTIONAL,
            'Type name',
            ''
        );

        $this->addOption(
            self::ALL,
            'a',
            InputOption::VALUE_NONE,
            'Delete all (uploaded too)',
            null
        );

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->consoleLogger->setConsoleOutput($output);
        $type = $input->getArgument(self::TYPE);
        $all = (bool)$input->getOption(self::ALL);
        if ($all) {
            $this->deleteAllActivity->execute($type);
        } else {
            $this->deleteActivity->execute($type);
        }
    }
}
