<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCleaner\Cron;

use DateInterval;
use DateTime;
use Monolog\Logger;
use Websolute\TransporterBase\Exception\TransporterException;
use Websolute\TransporterCleaner\Model\ConfigInterface;
use Websolute\TransporterCleaner\Model\DeleteActivity;
use Websolute\TransporterCron\Api\CronInstanceInterface;

class CleanAll implements CronInstanceInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var DeleteActivity
     */
    private $deleteActivity;

    /**
     * @param Logger $logger
     * @param ConfigInterface $config
     * @param DeleteActivity $deleteActivity
     */
    public function __construct(
        Logger $logger,
        ConfigInterface $config,
        DeleteActivity $deleteActivity
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->deleteActivity = $deleteActivity;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $hoursToKeep = $this->config->getHoursToKeep();

            $untilDateTime = new DateTime('now');
            $untilDateTime->sub(new DateInterval('PT' . $hoursToKeep . 'H'));

            $count = $this->deleteActivity->execute('all', $untilDateTime, true, true, true);

            $this->logger->info(__(
                'Run cron job TransporterCleaner - cleaned %1 activities',
                $count
            ));
        } catch (TransporterException $e) {
            $this->logger->error(__(
                'Error while run cron job TransporterCleaner - error: %1',
                $e->getMessage()
            ));
        }
    }
}
