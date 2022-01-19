<?php namespace Iweb\RestrictionsShopaholic\Classes\Restriction;

use Igor\MultiVendor\Models\VendorStock;
use Lovata\OrdersShopaholic\Classes\Processor\CartProcessor;
use Lovata\OrdersShopaholic\Interfaces\CheckRestrictionInterface;
use Lovata\OrdersShopaholic\Models\ShippingRestriction;

/**
 * Class RestrictionByEmptyPostCode
 * @package Iweb\RestrictionsShopaholic\Classes\Restriction
 * @author Igor Tverdokhleb, igor-tv@mail.ru
 */
class RestrictionByEmptyPostCode implements CheckRestrictionInterface
{
    protected $obRestrictionItem;
    protected $sReceiverPostCode;

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
        $this->sReceiverPostCode = $this->getReceiverPostCode();
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
     * @return string
     */
    public function getReceiverPostCode(): string
    {
        $arShippingAddress = array_get(CartProcessor::instance()->getCartData(), 'shipping_address', []);

        if (empty($arShippingAddress)) return '';

        return CartProcessor::instance()->getCartData()['shipping_address']['postcode'];
    }

    /**
     * Check restriction of shipping type
     * @return bool
     */
    public function check(): bool
    {
        if (!$this->obRestrictionItem->active) return true;

        if (!empty($this->sReceiverPostCode)) return true;

        return false;
    }
}
