<?php

use CMW\Controller\Votes\VotesController;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate("votes.dashboard.title.rewards");
$description = LangManager::translate("votes.dashboard.desc");

/* @var \CMW\Entity\Votes\VotesRewardsEntity $rewards */
/* @var \CMW\Entity\Minecraft\MinecraftServerEntity[] $minecraftServers */
/* @var \CMW\Interface\Votes\IRewardMethod[] $rewardMethods */

?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-award"></i>
        <span class="m-lg-auto"><?= LangManager::translate("votes.dashboard.title.rewards") ?></span></h3>
</div>

<section class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
                <form method="post" action="">
                    <?php (new SecurityManager())->insertHiddenToken() ?>
                    <div>
                        <label for="title"><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?> :</label>
                        <div class="input-group">
                            <i class="fa-solid fa-heading"></i>
                            <input type="text" id="title" name="title" value="<?= $rewards->getTitle() ?>" required
                                   placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?>">
                        </div>
                    </div>
                    <div>
                        <label for="reward_type_selected">Type de récompenses :</label>
                        <select class="form-select" name="reward_type_selected" id="reward_type_selected" required>
                            <?php foreach ($rewardMethods as $rewardMethod): ?>
                                <option value="<?= $rewardMethod->varName() ?>" <?= $rewardMethod->varName() === $rewards->getVarName() ? "selected" : "" ?>><?= $rewardMethod->name() ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <?php foreach ($rewardMethods as $rewardMethod): ?>
                            <div class="reward-method" id="method-<?= $rewardMethod->varName() ?>" style="display: none;">
                                <input hidden="hidden" name="reward_type_method_var_name" value="<?= $rewardMethod->varName() ?>">
                                <?php $rewardMethod->includeRewardConfigWidgets($rewards->getRewardsId()); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary float-right">
                            <?= LangManager::translate("core.btn.edit") ?>
                        </button>
                    </div>
                </form>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('reward_type_selected');
        const rewardMethods = document.querySelectorAll('.reward-method');

        function updateRewardMethods() {
            rewardMethods.forEach(method => {
                method.style.display = 'none';
                disableFormElements(method, true);
            });

            const selectedValue = selectElement.value;
            const selectedMethod = document.getElementById('method-' + selectedValue);
            if (selectedMethod) {
                selectedMethod.style.display = 'block';
                disableFormElements(selectedMethod, false);
            }
        }

        function disableFormElements(container, disable) {
            const elements = container.querySelectorAll('input, select, textarea, button, fieldset, optgroup, option, datalist, output');
            elements.forEach(element => {
                if (disable) {
                    element.disabled = true;
                    if (element.hasAttribute('required')) {
                        element.setAttribute('data-required', 'true');
                        element.required = false;
                    }
                } else {
                    element.disabled = false;
                    if (element.getAttribute('data-required') === 'true') {
                        element.setAttribute('required', 'true');
                        element.removeAttribute('data-required');
                        element.required = true;
                    }
                }
            });
        }

        selectElement.addEventListener('change', updateRewardMethods);

        // Initialize the display based on the current selection
        updateRewardMethods();
    });
</script>
