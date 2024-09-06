<?php

namespace CMW\Implementation\Votes\Shop;

use CMW\Interface\Shop\IPriceTypeMethod;
use CMW\Model\Shop\Payment\ShopPaymentMethodSettingsModel;

class ShopPriceTypeMethodVotesImplementations implements IPriceTypeMethod
{
    public function name(): string
    {
        return ShopPaymentMethodSettingsModel::getInstance()->getSetting($this->varName() . '_name') ?? 'Points de votes';
    }

    // Must be the same as PaymentMethod Implementation VarName !!
    public function varName(): string
    {
        return 'votePoints';
    }
}
