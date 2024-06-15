<?php

namespace CMW\Implementation\Votes\Votes;

use CMW\Controller\Core\PackageController;
use CMW\Controller\Votes\Rewards\VotePointRandomController;
use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Interface\Votes\IRewardMethod;
use CMW\Manager\Env\EnvManager;
use CMW\Model\Shop\Payment\ShopPaymentMethodSettingsModel;
use JsonException;

class VoteRewardVotePointRandomImplementations implements IRewardMethod
{
    public function name(): string
    {

        if (!PackageController::isInstalled("Shop")){
            return "Points de votes aléatoires. (Nécessite le package Shop.)";
        }

        $url = $_SERVER['REQUEST_URI'];

        if (str_contains($url, 'votes/rewards')) {
            if (ShopPaymentMethodSettingsModel::getInstance()->getSetting('votePoints_name')) {
                return ShopPaymentMethodSettingsModel::getInstance()->getSetting('votePoints_name') . " (Aléatoire)";
            } else {
                return "Points de votes (Aléatoire)";
            }
        } else {
            return ShopPaymentMethodSettingsModel::getInstance()->getSetting('votePoints_name') ?? "Points de votes";
        }
    }

    public function varName(): string
    {
        return "votePointsRandom";
    }

    public function includeRewardConfigWidgets(?int $rewardId): void
    {
        $varName = $this->varName();
        require_once EnvManager::getInstance()->getValue("DIR") . "App/Package/Votes/Views/Elements/votePointRandom.config.inc.view.php";
    }

    public function execRewardActionLogic(): ?string
    {
        try {
            $action = json_encode([
                "amount" => [
                    "min" => filter_input(INPUT_POST, $this->varName() . "_min"),
                    "max" => filter_input(INPUT_POST, $this->varName() . "_max")
                ]
            ], JSON_THROW_ON_ERROR);
        } catch (JsonException) {
        }
        return $action;
    }

    public function execReward(VotesRewardsEntity $reward, VotesSitesEntity $site, int $userId): void
    {
        VotePointRandomController::getInstance()->giveRandomVotePoints($reward, $userId);
    }
}