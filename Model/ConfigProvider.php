<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Razoyo\CarProfile\Api\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    /** @var ScopeConfigInterface  */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface  $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function isCarProfileEnabled($storeId = null, $scope = ScopeInterface::SCOPE_STORE): ?bool
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_CAR_PROFILE_ENABLED, $scope, $storeId);
    }

    /**
     * @inheritDoc
     */
    public function getCarApiEndpoint($storeId = null, $scope = ScopeInterface::SCOPE_STORE): ?string
    {
        return $this->scopeConfig->getValue(static::XML_PATH_CAR_PROFILE_ENDPOINT, $scope, $storeId);
    }
}