<?php

/* @var string $varName */
/* @var ?int $rewardId */

use CMW\Model\Votes\VotesRewardsModel;

if (!is_null($rewardId)) {
    $reward = VotesRewardsModel::getInstance()->getRewardById($rewardId);
    $action = $reward->getAction();
}

?>
<div class="mt-3 mb-4">
    <label for="<?= $varName ?>">Montant</label>
    <input value="<?= $action ?? '' ?>" placeholder="Montant" type="number" id="<?= $varName ?>" name="<?= $varName ?>" class="input" required>
</div>

