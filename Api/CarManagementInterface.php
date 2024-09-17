<?php

namespace Razoyo\CarProfile\Api;

interface CarManagementInterface
{
    const CUSTOMER_CAR_ATTRIBUTE = 'customer_car';

    /**
     * @param string|null $make
     * @return array|null
     */
    public function getCars(string $make = null): ?array;

    /**
     * @param string $id
     * @param string $token
     * @return array|null
     */
    public function getCarById(string $id, string $token): ?array;

    /**
     * @param string $carId
     * @return void
     */
    public function saveCar(string $carId): void;
}