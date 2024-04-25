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
                <label for="<?=$varName?>_name">Nom d'affichage :</label>
                <input value="<?= ShopPaymentMethodSettingsModel::getInstance()->getSetting($varName.'_name') ?? "Points de votes" ?>"
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
                <label for="<?=$varName?>_icon">Icon :</label>
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
        <button type="submit" class="btn btn-primary">Sauvegarder</button>
    </div>
</form>