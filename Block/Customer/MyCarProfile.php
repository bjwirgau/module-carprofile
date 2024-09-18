<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Block\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Psr\Log\LoggerInterface;
use Razoyo\CarProfile\Api\CarManagementInterface;

class MyCarProfile extends Template
{
    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private CustomerSession $customerSession;
    /**
     * @var \Api\CarManagementInterface
     */
    private CarManagementInterface $carManagement;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Razoyo\CarProfile\Api\CarManagementInterface $carManagement
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Psr\Log\LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CustomerRepositoryInterface $customerRepository,
        CarManagementInterface $carManagement,
        CustomerSession $customerSession,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->carManagement = $carManagement;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @return string|null
     */
    public function getCarProfileId(): ?string
    {
        try {
            $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
            $customerCarAttribute = $customer->getCustomAttribute(CarManagementInterface::CUSTOMER_CAR_ATTRIBUTE);

            return $customerCarAttribute?->getValue();
        } catch (LocalizedException $e) {
            $this->logger->debug(__('Failed to retrieve customer when loading car profile.'));
        }

        return null;
    }

    /**
     * @param string $profileId
     * @return array|null
     */
    public function getCarProfileData(string $profileId): ?array
    {
        $searchResults = $this->carManagement->getCars();

        if (!array_key_exists('header', $searchResults) &&
            !array_key_exists('your-token', $searchResults['header'])) {
            return [];
        }

        $carRequestData = $this->carManagement->getCarById($profileId, $searchResults['header']['your-token']);
        return $this->serializer->unserialize($carRequestData['body']);
    }
}