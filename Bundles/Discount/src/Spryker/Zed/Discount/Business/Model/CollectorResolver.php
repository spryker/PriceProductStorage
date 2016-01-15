<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\DiscountConfigInterface;

class CollectorResolver
{

    const OPERATOR_OR = 'OR';
    const OPERATOR_AND = 'AND';

    /**
     * @var DiscountConfigInterface
     */
    protected $discountConfig;

    /**
     * @param DiscountConfigInterface $discountConfig
     */
    public function __construct(DiscountConfigInterface $discountConfig)
    {
        $this->discountConfig = $discountConfig;
    }

    /**
     * @param CalculableInterface $container
     * @param DiscountTransfer $discountTransfer
     *
     * @return DiscountTransfer[]
     */
    public function collectItems(CalculableInterface $container, DiscountTransfer $discountTransfer)
    {
        $collectedItems = [];

        foreach ($discountTransfer->getDiscountCollectors() as $discountCollectorTransfer) {
            $collectorPlugin = $this->discountConfig->getCollectorPluginByName(
                $discountCollectorTransfer->getCollectorPlugin()
            );

            $itemsToCombine = $collectorPlugin->collect($discountTransfer, $container, $discountCollectorTransfer);

            if (!$this->isCombinable($itemsToCombine, $discountTransfer)) {
                return [];
            }

            $collectedItems = $this->combine($discountTransfer, $collectedItems, $itemsToCombine);
        }

        return $this->getUniqueDiscountableObjects($collectedItems);
    }

    /**
     * @param DiscountTransfer[] $discountableObjects
     *
     * @return DiscountTransfer[]
     */
    protected function getUniqueDiscountableObjects(array $discountableObjects)
    {
        $uniqueDiscountableObjects = [];
        foreach ($discountableObjects as $discountableObject) {
            $uniqueDiscountableObjects[spl_object_hash($discountableObject)] = $discountableObject;
        }

        return $uniqueDiscountableObjects;
    }

    /**
     * @param DiscountTransfer[] $collectedItems
     * @param DiscountTransfer[] $itemsToCombine
     *
     * @return DiscountTransfer[]
     */
    protected function combineWithAnd(array $collectedItems, array $itemsToCombine)
    {
        $collectedItems = array_uintersect(
            $collectedItems,
            $itemsToCombine,
            function ($collected, $toCollect) {
                return strcmp(spl_object_hash($collected), spl_object_hash($toCollect));
            }
        );

        return $collectedItems;
    }

    /**
     * @param DiscountableInterface[] $collectedItems
     * @param DiscountableInterface[] $itemsToCombine
     *
     * @return DiscountableInterface[]
     */
    protected function combineWithOr($collectedItems, $itemsToCombine)
    {
        $collectedItems = array_merge($collectedItems, $itemsToCombine);

        return $collectedItems;
    }

    /**
     * @param DiscountTransfer $discountTransfer
     * @param DiscountTransfer[] $collectedItems
     * @param DiscountTransfer[] $itemsToCombine
     *
     * @return DiscountTransfer[]
     */
    protected function combine(DiscountTransfer $discountTransfer, array $collectedItems, array $itemsToCombine)
    {
        if (empty($collectedItems)) {
            return $itemsToCombine;
        }

        if ($discountTransfer->getCollectorLogicalOperator() === self::OPERATOR_OR) {
            return $this->combineWithOr($collectedItems, $itemsToCombine);
        } else {
            return $this->combineWithAnd($collectedItems, $itemsToCombine);
        }
    }

    /**
     * @param DiscountTransfer[] $itemsToCombine
     * @param DiscountTransfer $discountTransfer
     *
     * @return bool
     */
    protected function isCombinable(array $itemsToCombine, DiscountTransfer $discountTransfer)
    {
        return (!empty($itemsToCombine) || $discountTransfer->getCollectorLogicalOperator() !== self::OPERATOR_AND);
    }

}