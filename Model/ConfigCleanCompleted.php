<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterCleaner\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigCleanCompleted implements ConfigInterface
{
    const TRANSPORTER_CLEANER_COMPLETED_IS_ENABLED_CONFIG_PATH = 'transporter/cleaner/cleaner_completed_enabled';
    const TRANSPORTER_CLEANER_COMPLETED_CRON_EXPRESSION_CONFIG_PATH = 'transporter/cleaner/cleaner_completed_cron_expression';
    const TRANSPORTER_CLEANER_COMPLETED_HOURS_TO_KEEP_CONFIG_PATH = 'transporter/cleaner/cleaner_completed_hours_to_keep';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isCronEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::TRANSPORTER_CLEANER_COMPLETED_IS_ENABLED_CONFIG_PATH,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getHoursToKeep(): string
    {
        return $this->scopeConfig->getValue(
            self::TRANSPORTER_CLEANER_COMPLETED_HOURS_TO_KEEP_CONFIG_PATH,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getCronExpression(): string
    {
        return $this->scopeConfig->getValue(
            self::TRANSPORTER_CLEANER_COMPLETED_CRON_EXPRESSION_CONFIG_PATH,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}
