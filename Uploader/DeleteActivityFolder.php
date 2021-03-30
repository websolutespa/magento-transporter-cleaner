<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCleaner\Uploader;

use Exception;
use Monolog\Logger;
use Websolute\TransporterBase\Api\TransporterConfigInterface;
use Websolute\TransporterBase\Api\UploaderInterface;

class DeleteActivityFolder implements UploaderInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var TransporterConfigInterface
     */
    private $config;

    /**
     * @var string
     */
    private $folderPath;

    /**
     * @var \Websolute\TransporterCleaner\Model\DeleteActivityFolder
     */
    private $deleteActivityFolder;

    /**
     * @param Logger $logger
     * @param TransporterConfigInterface $config
     * @param \Websolute\TransporterCleaner\Model\DeleteActivityFolder $deleteActivityFolder
     * @param string $folderPath
     */
    public function __construct(
        Logger $logger,
        TransporterConfigInterface $config,
        \Websolute\TransporterCleaner\Model\DeleteActivityFolder $deleteActivityFolder,
        string $folderPath
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->folderPath = $folderPath;
        $this->deleteActivityFolder = $deleteActivityFolder;
    }

    /**
     * @param int $activityId
     * @param string $uploaderType
     */
    public function execute(int $activityId, string $uploaderType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ START',
            $activityId,
            $uploaderType
        ));

        try {
            $this->deleteActivityFolder->execute($activityId, $this->folderPath);
        } catch (Exception $e) {
            $this->logger->error(__(
                'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ ERROR ~ error:%3',
                $activityId,
                $uploaderType,
                $e->getMessage()
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ END',
            $activityId,
            $uploaderType
        ));
    }
}
