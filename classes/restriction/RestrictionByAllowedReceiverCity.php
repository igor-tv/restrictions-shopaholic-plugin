<?php namespace Iweb\RestrictionsShopaholic\Classes\Restriction;

use Lovata\OrdersShopaholic\Classes\Processor\CartProcessor;
use Lovata\OrdersShopaholic\Interfaces\CheckRestrictionInterface;
use Lovata\OrdersShopaholic\Models\ShippingRestriction;

/**
 * Class RestrictionByAllowedReceiverCity
 * @package Iweb\RestrictionsShopaholic\Classes\Restriction
 * @author Igor Tverdokhleb, igor-tv@mail.ru
 */
class RestrictionByAllowedReceiverCity implements CheckRestrictionInterface
{
    protected $arAllowedReceiverCityList;
    protected $sCurrentReceiverCity;
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
        $sCityList = array_get($arProperty, 'allowed_receiver_city_list');
        $this->arAllowedReceiverCityList = $this->getArrCityListFromString($sCityList);
        $this->sCurrentReceiverCity = $this->getCurrentReceiverCity();
        $this->obRestrictionItem = ShippingRestriction::getByCode($sCode)->first();
    }

    /**
     * Get backend fields for restriction settings
     * @return array
     */
    public static function getFields(): array
    {
        return [
            'property[allowed_receiver_city_list]' => [
                'label'   => 'Allowed delivery cities',
                'tab'     => 'lovata.toolbox::lang.tab.settings',
                'span'    => 'left',
                'type'    => 'textarea',
                'comment' => 'List of cities separated by semicolon',
            ]
        ];
    }

    /**
     * @param string $sCityList
     * @return array
     */
    public function getArrCityListFromString(string $sCityList): array
    {
        if (empty($sCityList)) return  [];

        return $arCityList = preg_split("/\s*;\s*/", $sCityList);
    }

    /**
     * @return string
     */
    public function getCurrentReceiverCity(): string
    {
        $arShippingAddress = array_get(CartProcessor::instance()->getCartData(), 'shipping_address', []);

        if (empty($arShippingAddress)) return '';

        return array_get($arShippingAddress, 'city', '');
    }

    /**
     * Check restriction of shipping type
     * @return bool
     */
    public function check(): bool
    {
        if (!$this->obRestrictionItem->active) return true;

        if (empty($this->sCurrentReceiverCity) or empty($this->arAllowedReceiverCityList)) return false;

        return in_array($this->sCurrentReceiverCity, $this->arAllowedReceiverCityList);
    }
}
