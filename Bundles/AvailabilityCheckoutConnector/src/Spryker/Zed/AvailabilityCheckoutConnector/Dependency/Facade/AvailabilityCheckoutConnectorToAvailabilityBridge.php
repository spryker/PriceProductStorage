<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AvailabilityCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Availability\Business\AvailabilityFacade;

class AvailabilityCheckoutConnectorToAvailabilityBridge implements AvailabilityCheckoutConnectorToAvailabilityInterface
{

    /**
     * @var AvailabilityFacade
     */
    protected $availabilityFacade;

    /**
     * @param AvailabilityFacade $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    public function isProductSellable($sku, $quantity)
    {
        return $this->availabilityFacade->isProductSellable($sku, $quantity);
    }

}