<?php

use CMW\Controller\Votes\Admin\VotesRewardsController;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Log;

$title = LangManager::translate("votes.dashboard.title.rewards");
$description = LangManager::translate("votes.dashboard.desc");

/* @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
/* @var \CMW\Entity\Minecraft\MinecraftServerEntity[] $minecraftServers */
/* @var \CMW\Interface\Votes\IRewardMethod[] $rewardMethods */

?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-award"></i> <span
            class="m-lg-auto"><?= LangManager::translate("votes.dashboard.title.rewards") ?></span></h3>
</div>

<section class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.rewards.add.title") ?></h4>
            </div>
            <div class="card-body">
                <form method="post" action="rewards/add">
                    <?php (new SecurityManager())->insertHiddenToken() ?>
                    <h6><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" name="title" value="" required
                               placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?>">
                        <div class="form-control-icon">
                            <i class="fas fa-heading"></i>
                        </div>
                    </div>
                    <h6><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.type") ?></h6>
                    <select class="form-select" name="reward_type_selected" required>
                        <?php foreach ($rewardMethods as $rewardMethod): ?>
                            <option value="<?= $rewardMethod->varName() ?>" <?= $rewardMethod->varName() === "nothing" ? "selected" : "" ?>><?= $rewardMethod->name() ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="col-12">
                        <div class="tab-content text-justify" id="nav-tabContent">
                            <?php foreach ($rewardMethods as $rewardMethod): ?>
                                <div class="tab-pane" id="method-<?= $rewardMethod->varName() ?>">
                                    <input hidden="hidden" name="reward_type_method_var_name" value="<?= $rewardMethod->varName() ?>">
                                    <?php $rewardMethod->includeRewardConfigWidgets(null); ?>
                                </div>
                                <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary float-right">
                            <?= LangManager::translate("core.btn.add") ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.title.list_sites") ?></h4>
            </div>
            <div class="card-body">
                <table class="table" id="table1">
                    <thead>
                    <tr>
                        <th class="text-center"><?= LangManager::translate("votes.dashboard.table.name") ?></th>
                        <th class="text-center"><?= LangManager::translate("votes.dashboard.table.type") ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php foreach ($rewards as $reward) : ?>
                        <tr>
                            <td><?= $reward->getTitle() ?></td>
                            <td><?= VotesRewardsController::getInstance()->getRewardMethodByVarName($reward->getVarName())->name() ?></td>
                            <td>
                                <a href="rewards/edit/<?= $reward->getRewardsId() ?>">
                                    <i class="text-primary me-3 fas fa-edit"></i>
                                </a>
                                <a type="button" data-bs-toggle="modal"
                                   data-bs-target="#delete-<?= $reward->getRewardsId() ?>">
                                    <i class="text-danger fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <div class="modal fade text-left" id="delete-<?= $reward->getRewardsId() ?>" tabindex="-1"
                             role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title white"
                                            id="myModalLabel160"><?= LangManager::translate("votes.dashboard.modal.delete") ?> <?= $reward->getTitle() ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        <?= LangManager::translate("votes.dashboard.modal.deletealertreward") ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span
                                                class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                        </button>
                                        <a href="rewards/delete/<?= $reward->getRewardsId() ?>"
                                           class="btn btn-danger ml-1">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span
                                                class="d-none d-sm-block"><?= LangManager::translate("core.btn.delete") ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var selectElement = document.querySelector('[name="reward_type_selected"]');

        function toggleTabContent(value) {
            // Masquer tous les contenus d'onglets et désactiver tous les éléments de formulaire
            document.querySelectorAll('.tab-pane').forEach(function (tabContent) {
                tabContent.style.display = 'none';
                var formElements = tabContent.querySelectorAll('input, select, textarea, button');
                formElements.forEach(function (element) {
                    element.disabled = true;
                    // Enlever l'attribut required si présent
                    if (element.hasAttribute('required')) {
                        element.removeAttribute('required');
                    }
                });
            });

            // Si la valeur sélectionnée n'est pas "0", afficher le contenu correspondant et activer les éléments de formulaire
            if (value !== "0") {
                var activeTabContent = document.getElementById('method-' + value);
                if (activeTabContent) {
                    activeTabContent.style.display = 'block';
                    var formElements = activeTabContent.querySelectorAll('input, select, textarea, button');
                    formElements.forEach(function (element) {
                        element.disabled = false;
                        // Restaurer l'attribut required si nécessaire
                        if (element.dataset.originalRequired === "true") {
                            element.setAttribute('required', 'required');
                        }
                    });
                }
            }
        }

        // Initialiser sans afficher de contenu
        toggleTabContent(selectElement.value);

        // Écouteur d'événements pour le changement de sélection
        selectElement.addEventListener('change', function () {
            toggleTabContent(this.value);
        });
    });
</script>