<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Razoyo\CarProfile\Api\CarManagementInterface;
use Razoyo\CarProfile\Api\CarRequestInterface;
use Razoyo\CarProfile\Api\ConfigProviderInterface;

class CarManagement implements CarManagementInterface
{
    /**
     * @var \Api\ConfigProviderInterface
     */
    private ConfigProviderInterface $configProvider;
    /**
     * @var \Api\CarRequestInterface
     */
    private CarRequestInterface $carRequest;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private CustomerSession $customerSession;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private ManagerInterface $messageManager;
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(
        CarRequestInterface $carRequest,
        ConfigProviderInterface $configProvider,
        CustomerRepositoryInterface $customerRepository,
        CustomerSession $customerSession,
        ManagerInterface $messageManager,
        SerializerInterface $serializer
    ) {
        $this->configProvider = $configProvider;
        $this->carRequest = $carRequest;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->serializer = $serializer;
    }

    /**
     * @param string|null $make
     * @return array|null
     */
    public function getCars(string $make = null): ?array
    {
        $params = $make ? [
            'make' => $make
        ] : null;

        $url = $this->carRequest->buildRequestUrl(
            $this->configProvider->getCarApiEndpoint(),
            $params
        );

        return $this->carRequest->request($url, null);
    }

    /**
     * @param string $id
     * @param string $token
     * @return array|null
     */
    public function getCarById(string $id, string $token): ?array
    {
        $url = $this->carRequest->buildRequestUrl(
            $this->configProvider->getCarApiEndpoint() . '/' . $id,
            null
        );

        return $this->carRequest->request($url, $token);
    }

    /**
     * @param string $carId
     * @return void
     */
    public function saveCar(string $carId): void
    {
        try {
            $customerData = $this->customerRepository->getById(
                $this->customerSession->getCustomerId()
            );
            $customerData->setCustomAttribute(self::CUSTOMER_CAR_ATTRIBUTE, $carId);
            $this->customerRepository->save($customerData);
            $this->messageManager->addSuccessMessage(__('Your car has been saved.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__('Error saving car.'));
        }
    }
}