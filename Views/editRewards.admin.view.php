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
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.rewards.add.title") ?></h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php (new SecurityManager())->insertHiddenToken() ?>
                    <h6><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" name="title" value="<?= $rewards->getTitle() ?>" required
                               placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?>">
                        <div class="form-control-icon">
                            <i class="fas fa-heading"></i>
                        </div>
                    </div>
                    <h6>Type de récompenses :</h6>
                    <select class="form-select" name="reward_type_selected" required>
                        <?php foreach ($rewardMethods as $rewardMethod): ?>
                            <option value="<?= $rewardMethod->varName() ?>" <?= $rewardMethod->varName() === $rewards->getVarName() ? "selected" : "" ?>><?= $rewardMethod->name() ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="col-12">
                        <div class="tab-content text-justify" id="nav-tabContent">
                            <?php foreach ($rewardMethods as $rewardMethod): ?>
                                <div class="tab-pane" id="method-<?= $rewardMethod->varName() ?>">
                                    <input hidden="hidden" name="reward_type_method_var_name" value="<?= $rewardMethod->varName() ?>">
                                    <?php $rewardMethod->includeRewardConfigWidgets($rewards->getRewardsId()); ?>
                                </div>
                                <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary float-right">
                            <?= LangManager::translate("core.btn.edit") ?>
                        </button>
                    </div>
                </form>
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