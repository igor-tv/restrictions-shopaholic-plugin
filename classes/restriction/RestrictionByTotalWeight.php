<?php namespace Iweb\RestrictionsShopaholic\Classes\Restriction;

use Lovata\OrdersShopaholic\Classes\Processor\CartProcessor;
use Lovata\OrdersShopaholic\Interfaces\CheckRestrictionInterface;
use Lovata\OrdersShopaholic\Models\ShippingRestriction;

/**
 * Class RestrictionByTotalWeight
 * @package Iweb\RestrictionsShopaholic\Classes\Restriction
 * @author Igor Tverdokhleb, igor-tv@mail.ru
 */
class RestrictionByTotalWeight implements CheckRestrictionInterface
{
    protected $obRestrictionItem;
    protected $fMinWeight;
    protected $fMaxWeight;
    protected $fTotalWeight;

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

        $this->fMinWeight = (float) array_get($arProperty, 'weight_min');
        $this->fMaxWeight = (float) array_get($arProperty, 'weight_max');

        $this->fTotalWeight = CartProcessor::instance()->getCartData()['weight'];
    }

    /**
     * Get backend fields for restriction settings
     * @return array
     */
    public static function getFields(): array
    {
        return [
            'property[weight_min]' => [
                'label'   => 'Minimum weight',
                'tab'     => 'lovata.toolbox::lang.tab.settings',
                'span'    => 'left',
                'type'    => 'number',
                'context' => ['update', 'preview']
            ],
            'property[weight_max]' => [
                'label'   => 'Max weight',
                'tab'     => 'lovata.toolbox::lang.tab.settings',
                'span'    => 'right',
                'type'    => 'number',
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
        if (!$this->obRestrictionItem->active) return true;

        $bResult = $this->fTotalWeight >= $this->fMinWeight && ($this->fMaxWeight == 0 || $this->fTotalWeight <= $this->fMaxWeight);

        return $bResult;
    }
}
