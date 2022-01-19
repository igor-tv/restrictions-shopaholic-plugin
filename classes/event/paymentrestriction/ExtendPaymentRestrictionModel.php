<?php namespace Iweb\RestrictionsShopaholic\Classes\Event\PaymentRestriction;

use Event;
use Iweb\RestrictionsShopaholic\Classes\Restriction\RestrictionByAllowedCurrency;
use Lovata\OrdersShopaholic\Models\PaymentRestriction;

/**
 * Class ExtendPaymentRestrictionModel
 * @package Iweb\RestrictionsShopaholic\Classes\Event\PaymentRestriction
 * @author  Igor Tverdokhleb, igor-tv@mail.ru
 */
class ExtendPaymentRestrictionModel
{
    /**
     * Add listeners
     */
    public function subscribe()
    {
        Event::listen(PaymentRestriction::EVENT_GET_PAYMENT_RESTRICTION_LIST, function () {
            return [
                RestrictionByAllowedCurrency::class => 'By allowed currencies',
            ];
        });
    }
}
