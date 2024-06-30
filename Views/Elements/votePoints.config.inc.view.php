<?php

/* @var string $varName */

use CMW\Manager\Security\SecurityManager;
use CMW\Model\Shop\Payment\ShopPaymentMethodSettingsModel;

?>

<form id="votePoints" action="payments/settings" method="post">
    <?php (new SecurityManager())->insertHiddenToken(); ?>

<div class="grid-2">
    <div>
        <label for="<?=$varName?>_name">Nom d'affichage :</label>
        <input value="<?= ShopPaymentMethodSettingsModel::getInstance()->getSetting($varName.'_name') ?? "Points de votes" ?>"
               placeholder="Token"
               type="text"
               name="<?=$varName?>_name"
               id="<?=$varName?>_name"
               class="input"
               required>
    </div>
    <div>
        <div class="icon-picker" data-id="<?=$varName?>_icon" data-name="<?=$varName?>_icon" data-label="Icon :" data-placeholder="Sélectionner un icon" data-value="<?= ShopPaymentMethodSettingsModel::getInstance()->getSetting($varName.'_icon') ?? "fa-solid fa-coins" ?>"></div>
    </div>
</div>
    <button type="submit" class="btn-center btn-primary">Sauvegarder</button>
</form>