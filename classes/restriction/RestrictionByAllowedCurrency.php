<?php namespace Iweb\RestrictionsShopaholic\Classes\Restriction;

use Lovata\OrdersShopaholic\Interfaces\CheckRestrictionInterface;
use Lovata\OrdersShopaholic\Models\PaymentRestriction;
use Lovata\Shopaholic\Models\Currency;
use Lovata\Shopaholic\Classes\Helper\CurrencyHelper;

/**
 * Class RestrictionByAllowedCurrency
 * @package Iweb\RestrictionsShopaholic\Classes\Restriction
 * @author Igor Tverdokhleb, igor-tv@mail.ru
 */
class RestrictionByAllowedCurrency implements CheckRestrictionInterface
{
    protected $obRestrictionItem;
    protected $arAvailableCurrencies;
    protected $iCurrentCurrencyId;

    /**
     * CheckRestrictionInterface constructor.
     * @param \Lovata\OrdersShopaholic\Classes\Item\PaymentMethodItem $obPaymentMethodItem
     * @param array                                                   $arData
     * @param array                                                   $arProperty
     * @param string                                                  $sCode
     */
    public function __construct($obPaymentMethodItem, $arData, $arProperty, $sCode)
    {
        $this->obRestrictionItem = PaymentRestriction::getByCode($sCode)->first();
        $this->arAvailableCurrencies = (array) array_get($arProperty, 'currency');
        $this->iCurrentCurrencyId = CurrencyHelper::instance()->getActive()->id;
    }

    /**
     * Get backend fields for restriction settings
     * @return array
     */
    public static function getFields(): array
    {
        return [
            'property[currency]' => [
                'label'   => 'Currency',
                'tab'     => 'lovata.toolbox::lang.tab.settings',
                'span'    => 'left',
                'type'    => 'checkboxlist',
                'options' => Currency::pluck('name', 'id'),
                'context' => ['update', 'preview']
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

        return in_array($this->iCurrentCurrencyId, $this->arAvailableCurrencies);
    }
}
