<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCleaner\Model;

use DateTime;
use Exception;
use Websolute\TransporterActivity\Model\ActivityModel;
use Websolute\TransporterActivity\Model\ActivityStateInterface;
use Websolute\TransporterActivity\Model\ResourceModel\Activity\ActivityCollectionFactory;
use Websolute\TransporterActivity\Model\ResourceModel\ActivityResourceModel;
use Websolute\TransporterBase\Exception\TransporterException;

class DeleteActivity
{
    /**
     * @var ActivityCollectionFactory
     */
    private $activityCollectionFactory;

    /**
     * @var ActivityResourceModel
     */
    private $activityResourceModel;

    /**
     * @param ActivityCollectionFactory $activityCollectionFactory
     * @param ActivityResourceModel $activityResourceModel
     */
    public function __construct(
        ActivityCollectionFactory $activityCollectionFactory,
        ActivityResourceModel $activityResourceModel
    ) {
        $this->activityCollectionFactory = $activityCollectionFactory;
        $this->activityResourceModel = $activityResourceModel;
    }

    /**
     * @param string $type
     * @param DateTime $untilAt
     * @param bool $deleteUploaded
     * @param bool $deleteErrors
     * @param bool $deleteWorking
     * @return int
     * @throws TransporterException
     */
    public function execute(
        string $type = '',
        DateTime $untilAt = null,
        bool $deleteUploaded = true,
        bool $deleteErrors = false,
        bool $deleteWorking = false
    ): int {
        $activityCollection = $this->activityCollectionFactory->create();

        if ($type != '' && $type !== 'all') {
            $activityCollection->addFieldToFilter(ActivityModel::TYPE, ['eq' => $type]);
        }

        if ($untilAt) {
            $activityCollection->addFieldToFilter(ActivityModel::CREATED_AT, ['lt' => $untilAt]);
        }

        if (!$deleteUploaded && !$deleteErrors && !$deleteWorking) {
            throw new TransporterException(__('Nothing to delete with this call!'));
        }

        $statuses = [];

        if ($deleteUploaded) {
            $statuses[] = ActivityStateInterface::UPLOADED;
        }

        if ($deleteErrors) {
            $statuses[] = ActivityStateInterface::DOWNLOAD_ERROR;
            $statuses[] = ActivityStateInterface::MANIPULATE_ERROR;
            $statuses[] = ActivityStateInterface::UPLOAD_ERROR;
        }

        if ($deleteWorking) {
            $statuses[] = ActivityStateInterface::DOWNLOADING;
            $statuses[] = ActivityStateInterface::DOWNLOADED;
            $statuses[] = ActivityStateInterface::MANIPULATING;
            $statuses[] = ActivityStateInterface::MANIPULATED;
            $statuses[] = ActivityStateInterface::UPLOADING;
        }

        $activityCollection->addFieldToFilter(
            ActivityModel::STATUS,
            ['in' => $statuses]
        );

        $activityCollection->getItems();

        $count = $activityCollection->count();
        if (!$count) {
            return 0;
        }

        /** @var ActivityModel $activity */
        foreach ($activityCollection as $activity) {
            $this->activityResourceModel->delete($activity);
        }

        return $count;
    }
}
