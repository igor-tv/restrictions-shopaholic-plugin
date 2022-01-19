<?php namespace Iweb\RestrictionsShopaholic;

use Event;
use System\Classes\PluginBase;

//Events
//ShippingRestrictions events
use Iweb\RestrictionsShopaholic\Classes\Event\ShippingRestriction\ExtendShippingRestrictionModel;
//PaymentRestriction events
use Iweb\RestrictionsShopaholic\Classes\Event\PaymentRestriction\ExtendPaymentRestrictionModel;

use System\Classes\PluginManager;


class Plugin extends PluginBase
{
    /** @var array Plugin dependencies */
    public $require = ['Lovata.Shopaholic', 'Lovata.OrdersShopaholic', 'Lovata.Toolbox'];

    public function boot()
    {
        $this->addEventListener();
    }

    /**
     * Add Listeners
     */
    protected function addEventListener()
    {
        //ShippingRestriction events
        Event::subscribe(ExtendShippingRestrictionModel::class);

        //PaymentRestriction events
        Event::subscribe(ExtendPaymentRestrictionModel::class);
    }
}
