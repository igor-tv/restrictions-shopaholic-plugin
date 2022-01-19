<?php namespace Iweb\RestrictionsShopaholic\Classes\Event\ShippingRestriction;

use Event;
use Iweb\RestrictionsShopaholic\Classes\Restriction\RestrictionByDeniedVendor;
use Iweb\RestrictionsShopaholic\Classes\Restriction\RestrictionByTotalWeight;
use Iweb\RestrictionsShopaholic\Classes\Restriction\RestrictionByEmptyOfferWeightSize;
use Iweb\RestrictionsShopaholic\Classes\Restriction\RestrictionByAllowedReceiverCity;
use Iweb\RestrictionsShopaholic\Classes\Restriction\RestrictionByAllowedReceiverCountry;
use Iweb\RestrictionsShopaholic\Classes\Restriction\RestrictionByDeniedReceiverCountry;
use Iweb\RestrictionsShopaholic\Classes\Restriction\RestrictionByEmptyPostCode;
use Lovata\OrdersShopaholic\Models\ShippingRestriction;

/**
 * Class ExtendShippingRestrictionModel
 * @package Iweb\RestrictionsShopaholic\Classes\Event\ShippingRestriction
 * @author  Igor Tverdokhleb, igor-tv@mail.ru
 */
class ExtendShippingRestrictionModel
{
    /**
     * Add listeners
     */
    public function subscribe()
    {
        Event::listen(ShippingRestriction::EVENT_GET_SHIPPING_RESTRICTION_LIST, function () {
            return [
                RestrictionByEmptyOfferWeightSize::class => 'Denied offers with empty weights/dimensions',
                RestrictionByTotalWeight::class => 'By total weight of products in cart',
                RestrictionByAllowedReceiverCountry::class => 'Allowed shipping countries',
                RestrictionByDeniedReceiverCountry::class => 'Denied shipping countries',
                RestrictionByAllowedReceiverCity::class => 'Allowed shipping cities',
                RestrictionByEmptyPostCode::class => 'Denied empty receiver postcode',
            ];
        });
    }
}
