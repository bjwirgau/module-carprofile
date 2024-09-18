<?php
declare(strict_types=1);

namespace Razoyo\CarProfile\Block\Customer;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;

class MyCar extends Template
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\UrlInterface $urlBuilder
     */
    public function __construct(
        Template\Context $context,
        UrlInterface $urlBuilder
    ) {
        parent::__construct($context);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return string|null
     */
    public function getSearchCarUrl(): ?string
    {
       return $this->urlBuilder->getUrl(null,[
           '_secure'    => true,
           '_direct'    => 'rest/V1/razoyo/cars'
       ]);
    }

    /**
     * @return string|null
     */
    public function getSaveCarUrl(): ?string
    {
        return $this->urlBuilder->getUrl(null, [
            '_secure'   => true,
            '_direct'   => 'rest/V1/razoyo/car',
        ]);
    }
}