<?php

/* @var string $varName */
/* @var ?int $rewardId */

use CMW\Model\Votes\VotesRewardsModel;

if (!is_null($rewardId)) {
    $reward = VotesRewardsModel::getInstance()->getRewardById($rewardId);
    $action = $reward->getAction();

    try {
        $data = json_decode($action, false, 512, JSON_THROW_ON_ERROR);
        $min = $data->amount->min ?? 0;
        $max = $data->amount->max ?? 0;
    } catch (JsonException $e) {
        $min = 0;
        $max = 0;
    }
}
?>
<div class="mt-3 mb-4 row">
        <div class="col-12 col-lg-6">
            <label for="<?=$varName?>_min">Mini :</label>
            <input value="<?=$min ?? ""?>" placeholder="Montant" type="number" id="<?=$varName?>_min" name="<?=$varName?>_min" class="input" required>
        </div>
        <div class="col-12 col-lg-6">
            <label for="<?=$varName?>_max">Maxi :</label>
                <input value="<?=$max ?? ""?>" placeholder="Montant" type="number" id="<?=$varName?>_max" name="<?=$varName?>_max" class="input" required>
        </div>
</div>
