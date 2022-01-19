<?php namespace Iweb\RestrictionsShopaholic\Classes\Restriction;

use Lovata\OrdersShopaholic\Classes\Processor\CartProcessor;
use Lovata\OrdersShopaholic\Interfaces\CheckRestrictionInterface;
use Lovata\OrdersShopaholic\Models\ShippingRestriction;
use RainLab\Location\Models\Country;

/**
 * Class RestrictionByAllowedReceiverCountry
 * @package Iweb\RestrictionsShopaholic\Classes\Restriction
 * @author Igor Tverdokhleb, igor-tv@mail.ru
 */
class RestrictionByAllowedReceiverCountry implements CheckRestrictionInterface
{
    protected $arAllowedReceiverCountryList;
    protected $iCurrentReceiverCountryID;
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
        $this->arAllowedReceiverCountryList = array_get($arProperty, 'allowed_receiver_country_list');
        $this->iCurrentReceiverCountryID = $this->getReceiverCountryID();
    }

    /**
     * Get backend fields for restriction settings
     * @return array
     */
    public static function getFields(): array
    {
        return [
            'property[allowed_receiver_country_list]' => [
                'label'   => 'Countries',
                'tab'     => 'lovata.toolbox::lang.tab.settings',
                'span'    => 'left',
                'type'    => 'checkboxlist',
                'context' => ['update', 'preview'],
                'options' => Country::getNameList()
            ],
        ];
    }

    /**
     * Check restriction of shipping type
     * @return bool
     */
    public function check(): bool
    {
        if (!$this->obRestrictionItem->active) {
            return true;
        }

        if (empty($this->iCurrentReceiverCountryID) or empty($this->arAllowedReceiverCountryList)) {
            return false;
        }

        if (in_array($this->iCurrentReceiverCountryID, $this->arAllowedReceiverCountryList)) {
            return true;
        }

        return false;
    }

    private function getReceiverCountryID()
    {
        $arCartData = CartProcessor::instance()->getCartData();
        $arShippingAddress = array_get($arCartData, 'shipping_address', null);

        if (empty($arShippingAddress)) {
            return null;
        }

        return array_get($arShippingAddress, 'country_id', null);
    }
}
