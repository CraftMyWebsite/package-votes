<?php

namespace CMW\Implementation\Votes;

use CMW\Controller\Shop\Admin\Payment\ShopPaymentsController;
use CMW\Controller\Votes\VotesPaymentController;
use CMW\Entity\Shop\Deliveries\ShopDeliveryUserAddressEntity;
use CMW\Entity\Users\UserEntity;
use CMW\Interface\Shop\IPaymentMethod;
use CMW\Manager\Env\EnvManager;
use CMW\Model\Shop\Payment\ShopPaymentMethodSettingsModel;
use CMW\Model\Shop\Setting\ShopSettingsModel;
use CMW\Model\Users\UsersModel;
use CMW\Model\Votes\VotesStatsModel;

class ShopPaymentMethodeVotesImplementations implements IPaymentMethod
{
    public function name(): string
    {
        $url = $_SERVER['REQUEST_URI'];

        if (str_contains($url, 'shop/command')) {
            $tokenStock = VotesStatsModel::getInstance()->getVotePointByUserId(UsersModel::getCurrentUser()->getId());
            return ShopPaymentMethodSettingsModel::getInstance()->getSetting($this->varName().'_name')." - Solde: $tokenStock" ?? "Points de votes - Solde: $tokenStock";
        } else {
            return ShopPaymentMethodSettingsModel::getInstance()->getSetting($this->varName().'_name') ?? "Points de votes";
        }
    }

    //Must be the same as PriceType Implementation VarName !!
    public function varName(): string
    {
        return "votePoints";
    }

    public function faIcon(?string $customClass = null): ?string
    {
        $icon = ShopPaymentMethodSettingsModel::getInstance()->getSetting($this->varName().'_icon') ?? "fa-solid fa-ticket";
        return "<i class='$icon $customClass'></i>";
    }

    public function dashboardURL(): ?string
    {
        return null;
    }

    public function documentationURL(): ?string
    {
        return null;
    }

    public function description(): ?string
    {
        return null;
    }

    public function fees(): float
    {
        return 0;
    }

    /**
     * @return string
     * @desc return the price for views
     */
    public function getFeesFormatted(): string
    {
        $formattedPrice = number_format($this->fees(), 2, '.', '');
        $symbol = ShopPaymentsController::getInstance()->getPaymentByVarName($this->varName())->faIcon();
        $symbolIsAfter = ShopSettingsModel::getInstance()->getSettingValue("after");
        if ($symbolIsAfter) {
            return $formattedPrice . " " .$symbol;
        } else {
            return $symbol . " " . $formattedPrice;
        }
    }

    public function isActive(): bool
    {
        return ShopPaymentMethodSettingsModel::getInstance()->getSetting($this->varName().'_is_active') ?? 1;
    }

    public function isVirtualCurrency(): bool
    {
        return 1;
    }

    public function includeConfigWidgets(): void
    {
        $varName = $this->varName();
        require_once EnvManager::getInstance()->getValue("DIR") . "App/Package/Votes/Views/Elements/votePoints.config.inc.view.php";
    }

    public function doPayment(array $cartItems, UserEntity $user, ShopDeliveryUserAddressEntity $address): void
    {
        VotesPaymentController::getInstance()->payByVotePoints($cartItems);
    }
}