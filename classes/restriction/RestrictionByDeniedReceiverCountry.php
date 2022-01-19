<?php namespace Iweb\RestrictionsShopaholic\Classes\Restriction;

use Lovata\OrdersShopaholic\Classes\Processor\CartProcessor;
use Lovata\OrdersShopaholic\Interfaces\CheckRestrictionInterface;
use Lovata\OrdersShopaholic\Models\ShippingRestriction;

/**
 * Class RestrictionByDeniedReceiverCountry
 * @package Iweb\RestrictionsShopaholic\Classes\Restriction
 * @author Igor Tverdokhleb, igor-tv@mail.ru
 */
class RestrictionByDeniedReceiverCountry implements CheckRestrictionInterface
{
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

    }

    /**
     * Get backend fields for restriction settings
     * @return array
     */
    public static function getFields(): array
    {
        return [];
    }

    /**
     * Check restriction of shipping type
     * @return bool
     */
    public function check(): bool
    {
        if (!$this->obRestrictionItem->active) return true;

        return false;
    }
}
