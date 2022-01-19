<?php namespace Iweb\RestrictionsShopaholic\Classes\Restriction;

use Igor\MultiVendor\Models\Vendor;
use Lovata\OrdersShopaholic\Classes\Item\CartPositionItem;
use Lovata\OrdersShopaholic\Classes\Processor\CartProcessor;
use Lovata\OrdersShopaholic\Interfaces\CheckRestrictionInterface;
use Lovata\OrdersShopaholic\Models\ShippingRestriction;

/**
 * Class RestrictionByEmptyOfferWeightSize
 * @package Iweb\RestrictionsShopaholic\Classes\Restriction
 * @author Igor Tverdokhleb, igor-tv@mail.ru
 */
class RestrictionByEmptyOfferWeightSize implements CheckRestrictionInterface
{
    protected $bCartPositionsHasEmptyWeightSizeValue;
    protected $obRestrictionItem;

    /**
     * CheckRestrictionInterface constructor.
     * @param \Lovata\OrdersShopaholic\Classes\Item\ShippingTypeItem $obShippingTypeItem
     * @param array                                                  $arData
     * @param array                                                  $arProperty
     * @param string                                                 $sCode
     */
    public function __construct($obShippingTypeItem, $arData, $arProperty, $sCode)
    {
        $this->obRestrictionItem = ShippingRestriction::getByCode($sCode)->first();
        $this->bCartPositionsHasEmptyWeightSizeValue = $this->getCartPositionEmptyWeightSizeValues();
    }

    /**
     * Get backend fields for restriction settings
     * @return array
     */
    public static function getFields(): array
    {
        return [];
    }

    public function getCartPositionEmptyWeightSizeValues(): bool
    {
        $bHasEmptyWeightSizeValue = false;

        $obCartPositionCollection = CartProcessor::instance()->get();

        /** @var CartPositionItem $obCartPositionItem */
        foreach ($obCartPositionCollection as $obCartPositionItem) {
            $dOfferWidth  = $obCartPositionItem->offer->width;
            $dOfferLength = $obCartPositionItem->offer->length;
            $dOfferHeight = $obCartPositionItem->offer->height;
            $dOfferWeight = $obCartPositionItem->offer->weight;

            if (empty($dOfferLength)) {
                $bHasEmptyWeightSizeValue = true;
            }
            if (empty($dOfferWidth)) {
                $bHasEmptyWeightSizeValue = true;
            }
            if (empty($dOfferHeight)) {
                $bHasEmptyWeightSizeValue = true;
            }
            if (empty($dOfferWeight)) {
                $bHasEmptyWeightSizeValue = true;
            }
        }

        return $bHasEmptyWeightSizeValue;
    }

    /**
     * Check restriction of vendor list
     * @return bool
     */
    public function check(): bool
    {
        if (!$this->obRestrictionItem->active) {
            return true;
        }

        if (!$this->bCartPositionsHasEmptyWeightSizeValue) {
            return true;
        }

        return false;
    }
}
