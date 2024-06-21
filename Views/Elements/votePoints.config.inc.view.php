<?php

/* @var string $varName */

use CMW\Manager\Security\SecurityManager;
use CMW\Model\Shop\Payment\ShopPaymentMethodSettingsModel;

?>

<form id="votePoints" action="payments/settings" method="post">
    <?php (new SecurityManager())->insertHiddenToken(); ?>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="<?=$varName?>_name"><?= LangManager::translate("votes.dashboard.rewards.votepoints.nameaff") ?></label>
                <input value="<?= ShopPaymentMethodSettingsModel::getInstance()->getSetting($varName.'_name') ?? <?= LangManager::translate("votes.dashboard.rewards.votepoints.name") ?>
                       placeholder="Token"
                       type="text"
                       name="<?=$varName?>_name"
                       id="<?=$varName?>_name"
                       class="form-control"
                       required>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="<?=$varName?>_icon"><?= LangManager::translate("votes.dashboard.rewards.votepoints.icon") ?></label>
                <input value="<?= ShopPaymentMethodSettingsModel::getInstance()->getSetting($varName.'_icon') ?? "fa-solid fa-coins" ?>"
                       placeholder="Token"
                       type="text"
                       name="<?=$varName?>_icon"
                       id="<?=$varName?>_icon"
                       class="form-control"
                       required>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary"><?= LangManager::translate("votes.dashboard.rewards.votepoints.save") ?></button>
    </div>
</form>