<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCleaner\Model;

use Exception;
use Websolute\TransporterActivity\Model\ActivityModel;
use Websolute\TransporterActivity\Model\ResourceModel\Activity\ActivityCollectionFactory;
use Websolute\TransporterActivity\Model\ResourceModel\ActivityResourceModel;

class DeleteAllActivity
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
     * @return int
     * @throws Exception
     */
    public function execute(
        string $type = ''
    ): int {
        $activityCollection = $this->activityCollectionFactory->create();

        if ($type != '') {
            $activityCollection->addFieldToFilter(ActivityModel::TYPE, ['eq' => $type]);
        }

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
