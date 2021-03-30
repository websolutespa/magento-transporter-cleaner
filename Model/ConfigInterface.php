<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCleaner\Model;

use Websolute\TransporterCron\Api\CronConfigInterface;

interface ConfigInterface extends CronConfigInterface
{
    /**
     * @return string
     */
    public function getHoursToKeep(): string;
}
