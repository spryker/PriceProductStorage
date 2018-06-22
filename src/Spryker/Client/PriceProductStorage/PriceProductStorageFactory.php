<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductStorage\Expander\ProductViewPriceExpander;
use Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReader;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReader;
use Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGenerator;

class PriceProductStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductStorage\Expander\ProductViewPriceExpanderInterface
     */
    public function createProductViewPriceExpander()
    {
        return new ProductViewPriceExpander(
            $this->createPriceAbstractStorageReader(),
            $this->createPriceConcreteStorageReader(),
            $this->getPriceProductService()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface
     */
    protected function createPriceAbstractStorageReader()
    {
        return new PriceAbstractStorageReader(
            $this->getStorage(),
            $this->createPriceProductStorageKeyGenerator(),
            $this->getPriceDimensionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface
     */
    protected function createPriceConcreteStorageReader()
    {
        return new PriceConcreteStorageReader(
            $this->getStorage(),
            $this->createPriceProductStorageKeyGenerator(),
            $this->getPriceDimensionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface
     */
    protected function getPriceProductClient()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientInterface
     */
    protected function getStoreClient()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGeneratorInterface
     */
    protected function createPriceProductStorageKeyGenerator()
    {
        return new PriceProductStorageKeyGenerator($this->getSynchronizationService(), $this->getStoreClient());
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface[]
     */
    public function getPriceDimensionPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::PLUGIN_STORAGE_PRICE_DIMENSION);
    }

    public function getPriceProductService()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::SERVICE_PRICE_PRODUCT);
    }
}
