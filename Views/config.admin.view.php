<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate("votes.dashboard.title.config");
$description = LangManager::translate("votes.dashboard.desc");

/** @var \CMW\Entity\Votes\VotesConfigEntity $config */
?>

<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-gears"></i> <span
            class="m-lg-auto"><?= LangManager::translate("votes.dashboard.title.config") ?></span></h3>
</div>

<section class="row">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.title.settings") ?></h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php (new SecurityManager())->insertHiddenToken() ?>
                    <h6><?= LangManager::translate("votes.dashboard.config.placeholder.top_show") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" name="topShow" value="<?= $config->getTopShow() ?>"
                               required autocomplete="off"
                               placeholder="<?= LangManager::translate("votes.dashboard.config.placeholder.top_show") ?>">
                        <div class="form-control-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                    <h6><?= LangManager::translate("votes.dashboard.config.placeholder.reset") ?> :</h6>
                    <div class="form-group position-relative">
                        <select name="reset" class="form-control" required>
                            <option value="0" <?= $config->getReset() === 0 ? 'selected' : '' ?>>
                                <?= LangManager::translate("votes.dashboard.config.reset.0") ?>
                            </option>
                            <option value="1" <?= $config->getReset() === 1 ? 'selected' : '' ?>>
                                <?= LangManager::translate("votes.dashboard.config.reset.1") ?>
                            </option>
                            <option value="2" <?= $config->getReset() === 2 ? 'selected' : '' ?>>
                                <?= LangManager::translate("votes.dashboard.config.reset.2") ?>
                            </option>
                        </select>
                    </div>
                    <h6><?= LangManager::translate("votes.dashboard.config.placeholder.enable_api") ?> :</h6>
                    <div class="form-group position-relative">
                        <select name="api" class="form-control" required>
                            <option value="1" <?= $config->isEnableApi() ? "selected" : "" ?>>
                                <?= LangManager::translate("votes.dashboard.config.enable_api.1") ?>
                            </option>

                            <option value="0" <?= !$config->isEnableApi() ? "selected" : "" ?>>
                                <?= LangManager::translate("votes.dashboard.config.enable_api.0") ?></option>
                        </select>
                    </div>

                    <div class="form-group position-relative">
                        <label class="form-check-label" for="needLogin">
                            <?= LangManager::translate("votes.dashboard.config.needLogin") ?>
                        </label>
                        <input class="form-check-input" type="checkbox" id="needLogin" name="needLogin"
                            <?= $config->isNeedLogin() ? 'checked' : '' ?>>
                    </div>

                    <input type="number" name="autoTopRewardActive" value="1" hidden>
                    <!-- /!\ JSON function /!\-->
                    <input type="text" name="autoTopReward" value='cc le JSON' hidden>

                    <!-- Récompenses automatique (mensuel)

                        <input type="checkbox" name="toggleAutoReward" value="checkbox" onchange="showMe('showAutoReward')" /> Activer les récompenses automatique

                        <div id="showAutoReward" style="display:none;">
                            Cc me voilà
                        </div>

                        <script type="text/javascript">

                            function showMe (box) {
                                var chboxs = document.getElementById("showAutoReward").style.display;
                                var vis = "none";
                                if(chboxs=="none"){
                                    vis = "block"; }
                                if(chboxs=="block"){
                                    vis = "none"; }
                                document.getElementById(box).style.display = vis;
                            }

                        </script>
                    -->
                    <div class="text-center">
                        <button type="submit"
                                class="btn btn-primary"><?= LangManager::translate("core.btn.save") ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>