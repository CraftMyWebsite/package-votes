<?php

namespace CMW\Implementation\Votes\Votes;

use CMW\Controller\Core\PackageController;
use CMW\Controller\Votes\Rewards\VotePointUniqueController;
use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Interface\Votes\IRewardMethod;
use CMW\Manager\Env\EnvManager;
use CMW\Model\Shop\Payment\ShopPaymentMethodSettingsModel;

class VoteRewardVotePointUniqueImplementations implements IRewardMethod
{

    public function name(): string
    {
        if (!PackageController::isInstalled("Shop")){
            return "Points de votes uniques. (Nécessite le package Shop.)";
        }

        $url = $_SERVER['REQUEST_URI'];

        if (str_contains($url, 'votes/rewards')) {
            if (ShopPaymentMethodSettingsModel::getInstance()->getSetting('votePoints_name')) {
                return ShopPaymentMethodSettingsModel::getInstance()->getSetting('votePoints_name')." (Unique)";
            } else {
                return "Points de votes (Unique)";
            }
        } else {
            return ShopPaymentMethodSettingsModel::getInstance()->getSetting('votePoints_name') ?? "Points de votes";
        }
    }

    public function varName(): string
    {
        return "votePoints";
    }

    public function includeRewardConfigWidgets(?int $rewardId): void
    {
        $varName = $this->varName();
        require_once EnvManager::getInstance()->getValue("DIR") . "App/Package/Votes/Views/Elements/votePointUnique.config.inc.view.php";
    }

    public function execRewardActionLogic(): ?string
    {
        return null;
    }

    public function execReward(VotesRewardsEntity $reward, VotesSitesEntity $site, int $userId): void
    {
        VotePointUniqueController::getInstance()->giveUniqueVotePoints($reward, $userId);
    }
}