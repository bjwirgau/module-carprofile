<?php

namespace Razoyo\CarProfile\Api;

use Magento\Store\Model\ScopeInterface;

interface ConfigProviderInterface
{
    const XML_PATH_CAR_PROFILE_ENABLED = 'razoyo_car_profile/general/enabled';
    const XML_PATH_CAR_PROFILE_ENDPOINT = 'razoyo_car_profile/general/endpoint';

    /**
     * @param null $storeId
     * @param string $scope
     * @return bool
     */
    public function isCarProfileEnabled($storeId = null, $scope = ScopeInterface::SCOPE_STORE): ?bool;

    /**
     * @param $storeId
     * @param $scope
     * @return string|null
     */
    public function getCarApiEndpoint($storeId = null, $scope = ScopeInterface::SCOPE_STORE): ?string;
}