<?php

use CMW\Controller\Votes\Admin\VotesRewardsController;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Log;

$title = LangManager::translate('votes.dashboard.title.rewards');
$description = LangManager::translate('votes.dashboard.desc');

/* @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
/* @var \CMW\Entity\Minecraft\MinecraftServerEntity[] $minecraftServers */
/* @var \CMW\Interface\Votes\IRewardMethod[] $rewardMethods */

?>
<h3><i class="fa-solid fa-award"></i> <?= LangManager::translate('votes.dashboard.title.rewards') ?></h3>

<div class="grid-2">
    <div class="card">

        <form method="post" action="rewards/add">
            <?php (new SecurityManager())->insertHiddenToken() ?>
            <div class="space-y-4">
                <h6><?= LangManager::translate('votes.dashboard.rewards.add.title') ?></h6>
                <div>
                    <label for="title"><?= LangManager::translate('votes.dashboard.rewards.add.placeholder.title') ?> :</label>
                    <div class="input-group">
                        <i class="fa-solid fa-heading"></i>
                        <input type="text" id="title" name="title" value="" required
                               placeholder="<?= LangManager::translate('votes.dashboard.rewards.add.placeholder.title') ?>">
                    </div>
                </div>
                <div>
                    <label for="reward_type_selected">Type de récompenses :</label>
                    <select class="form-select" name="reward_type_selected" id="reward_type_selected" required>
                        <?php foreach ($rewardMethods as $rewardMethod): ?>
                            <option value="<?= $rewardMethod->varName() ?>" <?= $rewardMethod->varName() === 'nothing' ? 'selected' : '' ?>>
                                <?= $rewardMethod->name() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <?php foreach ($rewardMethods as $rewardMethod): ?>
                        <div class="reward-method" id="method-<?= $rewardMethod->varName() ?>" style="display: none;">
                            <input hidden="hidden" name="reward_type_method_var_name" value="<?= $rewardMethod->varName() ?>">
                            <?php $rewardMethod->includeRewardConfigWidgets(null); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn-center btn-primary">
                <?= LangManager::translate('core.btn.add') ?>
            </button>
        </form>
    </div>
    <div class="card">
        <h6><?= LangManager::translate('votes.dashboard.rewards.list.title') ?></h6>
        <div class="table-container table-container-striped">
            <table class="table" id="table1">
                <thead>
                <tr>
                    <th><?= LangManager::translate('votes.dashboard.table.name') ?></th>
                    <th><?= LangManager::translate('votes.dashboard.table.type') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rewards as $reward): ?>
                    <tr>
                        <td><?= $reward->getTitle() ?></td>
                        <td><?= VotesRewardsController::getInstance()->getRewardMethodByVarName($reward->getVarName())->name() ?></td>
                        <td class="text-center space-x-2">
                            <a href="rewards/edit/<?= $reward->getRewardsId() ?>">
                                <i class="text-info fas fa-edit"></i>
                            </a>
                            <button data-modal-toggle="modal-delete-<?= $reward->getRewardsId() ?>" type="button"><i class="text-danger fas fa-trash-alt"></i></button>
                            <div id="modal-delete-<?= $reward->getRewardsId() ?>" class="modal-container">
                                <div class="modal">
                                    <div class="modal-header-danger">
                                        <h6><?= LangManager::translate('votes.dashboard.modal.delete') ?> <?= $reward->getTitle() ?></h6>
                                        <button type="button" data-modal-hide="modal-delete-<?= $reward->getRewardsId() ?>"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <?= LangManager::translate('votes.dashboard.modal.deletealertreward') ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="rewards/delete/<?= $reward->getRewardsId() ?>"
                                           class="btn-danger"><?= LangManager::translate('core.btn.delete') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
