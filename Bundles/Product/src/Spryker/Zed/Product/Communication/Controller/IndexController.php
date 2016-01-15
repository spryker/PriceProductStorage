<?php

namespace Spryker\Zed\Product\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Communication\ProductCommunicationFactory;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProduct;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductFacade getFacade()
 * @method ProductQueryContainer getQueryContainer()
 * @method ProductCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{

    const ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    const COL_ID_PRODUCT_CATEGORY = 'id_product_category';
    const COL_CATEGORY_NAME = 'category_name';

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createProductTable();

        return [
            'products' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createProductTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idProductAbstract = $request->query->getInt(self::ID_PRODUCT_ABSTRACT);

        $productAbstract = $this->getQueryContainer()
            ->querySkuFromProductAbstractById($idProductAbstract)
            ->findOne();

        $productConcreteCollection = $this->getQueryContainer()
            ->queryProductConcreteByProductAbstract($productAbstract)
            ->find();

        $productConcreteCollection = $this->createProductConcreteCollectionCollection($productConcreteCollection);

        $currentLocale = $this->getCurrentLocale();

        $attributesCollection = $this->getQueryContainer()
            ->queryProductAbstractAttributeCollection($productAbstract->getIdProductAbstract(), $currentLocale->getIdLocale())
            ->findOne();

        $attributes = [
            'name' => $attributesCollection->getName(),
            'attributes' => $this->mergeAttributes(
                json_decode($attributesCollection->getAttributes(), true),
                json_decode($productAbstract->getAttributes(), true)
            ),
        ];

        $categories = $this->getProductCategories($productAbstract, $currentLocale->getIdLocale());

        return $this->viewResponse([
            'productAbstract' => $productAbstract,
            'productConcreteCollection' => $productConcreteCollection,
            'attributes' => $attributes,
            'categories' => $categories,
        ]);
    }

    /**
     * @param array $attributes
     * @param array $prioritizedAttributes
     *
     * @return array
     */
    protected function mergeAttributes(array $attributes, array $prioritizedAttributes)
    {
        $attributes = array_merge($attributes, $prioritizedAttributes);

        return $attributes;
    }

    /**
     * @param SpyProduct $product
     *
     * @return array
     */
    protected function getProductPriceList(SpyProduct $product)
    {
        $productPricesCollection = $product->getPriceProductsJoinPriceType();

        // @todo this is here for proof of concept, will be changed
        $whiteListPrices = [1,5,10,20,50,100];

        $priceList = [];
        foreach ($productPricesCollection as $priceDefinition) {
            if (!in_array($priceDefinition->getPriceType()->getName(), $whiteListPrices)) {
                continue;
            }
            $priceList[] = [
                'name' => $priceDefinition->getPriceType()->getName(),
                'value' => $priceDefinition->getPrice(),
            ];
        }

        return $priceList;
    }

    /**
     * @param ObjectCollection|SpyProduct[] $productConcreteCollectionCollection
     *
     * @return array
     */
    protected function createProductConcreteCollectionCollection(ObjectCollection $productConcreteCollectionCollection)
    {
        $productConcreteCollection = [];
        foreach ($productConcreteCollectionCollection as $productConcrete) {
            $productOptions = $this->getFactory()
                ->createProductOptionsFacade()
                ->getProductOptionsByIdProduct(
                    $productConcrete->getIdProduct(),
                    $this->getCurrentLocale()->getIdLocale()
                );

            $productConcreteCollection[] = [
                'sku' => $productConcrete->getSku(),
                'idProduct' => $productConcrete->getIdProduct(),
                'isActive' => $productConcrete->getIsActive(),
                'priceList' => $this->getProductPriceList($productConcrete),
                'productOptions' => $productOptions,
            ];
        }

        return $productConcreteCollection;
    }

    /**
     * @param SpyProductAbstract $productAbstract
     * @param int $idLocale
     *
     * @return array
     */
    protected function getProductCategories(SpyProductAbstract $productAbstract, $idLocale)
    {
        $productCategoryEntityList = $this->getFactory()
            ->createProductCategoryQueryContainer()
            ->queryLocalizedProductCategoryMappingByIdProduct($productAbstract->getIdProductAbstract())
            ->find();

        $categories = [];
        foreach ($productCategoryEntityList as $productCategoryEntity) {
            $categories[] = [
                self::COL_ID_PRODUCT_CATEGORY => $productCategoryEntity->getIdProductCategory(),
                self::COL_CATEGORY_NAME => $productCategoryEntity->getSpyCategory()
                    ->getLocalisedAttributes($idLocale)
                    ->getFirst()
                    ->getName(),
            ];
        }

        return $categories;
    }

    /**
     * @throws \ErrorException
     *
     * @return LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()
            ->createLocaleFacade()
            ->getCurrentLocale();
    }

}