<?php

use CMW\Entity\Votes\VotesConfigEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate('votes.dashboard.title.config');
$description = LangManager::translate('votes.dashboard.desc');

/** @var VotesConfigEntity $config */
?>

<h3><?= LangManager::translate('votes.dashboard.title.config') ?></h3>

<div class="center-flex">
    <div class="flex-content-lg">
        <div class="card">
            <h6><?= LangManager::translate('votes.dashboard.title.settings') ?></h6>
            <form method="post">
                <?php SecurityManager::getInstance()->insertHiddenToken() ?>
                <div class="space-y-4">
                    <div>
                        <label for="top_show">
                            <?= LangManager::translate('votes.dashboard.config.placeholder.top_show') ?>:
                        </label>
                        <div class="input-group">
                            <i class="fa-solid fa-trophy"></i>
                            <input type="number" id="top_show" name="top_show" value="<?= $config->getTopShow() ?>"
                                   required autocomplete="off"
                                   placeholder="<?= LangManager::translate('votes.dashboard.config.placeholder.top_show') ?>">
                        </div>
                    </div>
                    <div>
                        <label for="reset">
                            <?= LangManager::translate('votes.dashboard.config.placeholder.reset') ?>:
                        </label>
                        <select id="reset" name="reset" required>
                            <option value="0" <?= $config->getReset() === 0 ? 'selected' : '' ?>>
                                <?= LangManager::translate('votes.dashboard.config.reset.0') ?>
                            </option>
                            <option value="1" <?= $config->getReset() === 1 ? 'selected' : '' ?>>
                                <?= LangManager::translate('votes.dashboard.config.reset.1') ?>
                            </option>
                            <option value="2" <?= $config->getReset() === 2 ? 'selected' : '' ?>>
                                <?= LangManager::translate('votes.dashboard.config.reset.2') ?>
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="api">
                            <?= LangManager::translate('votes.dashboard.config.placeholder.enable_api') ?>:
                        </label>
                        <select name="api" id="api" class="form-control" required>
                            <option value="1" <?= $config->isEnableApi() ? 'selected' : '' ?>>
                                <?= LangManager::translate('votes.dashboard.config.enable_api.1') ?>
                            </option>

                            <option value="0" <?= !$config->isEnableApi() ? 'selected' : '' ?>>
                                <?= LangManager::translate('votes.dashboard.config.enable_api.0') ?>
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="toggle">
                            <p class="toggle-label">
                                <?= LangManager::translate('votes.dashboard.config.needLogin') ?>
                            </p>
                            <input type="checkbox" value="1" class="toggle-input" id="need_login" name="need_login"
                                <?= $config->isNeedLogin() ? 'checked' : '' ?>>
                            <div class="toggle-slider"></div>
                        </label>
                    </div>
                    <input type="number" name="auto_top_reward_active" value="1" hidden>
                    <!-- /!\ JSON function /!\-->
                    <input type="text" name="auto_top_reward" value='cc le JSON' hidden>

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
                    <div>
                        <button type="submit" class="btn-center btn-primary">
                            <?= LangManager::translate('core.btn.save') ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>