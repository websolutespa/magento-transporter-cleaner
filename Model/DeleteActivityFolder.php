<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCleaner\Model;

class DeleteActivityFolder
{
    /**
     * @param int $activityId
     * @param string $folderPath
     */
    public function execute(int $activityId, string $folderPath): void
    {
        $path = rtrim($folderPath, DIRECTORY_SEPARATOR);
        $path .= DIRECTORY_SEPARATOR;
        $path .= $activityId;
        @rmdir($path);
    }
}
