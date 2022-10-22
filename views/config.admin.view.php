<?php

use CMW\Manager\Lang\LangManager;
use CMW\Utils\SecurityService;

$title = LangManager::translate("votes.dashboard.title.config");
$description = LangManager::translate("votes.dashboard.desc");

/** @var \CMW\Entity\Votes\VotesConfigEntity $config */
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <form action="" method="post">
                    <?php (new SecurityService())->insertHiddenToken() ?>
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?= LangManager::translate("votes.dashboard.title.config") ?> :</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-trophy"></i></span>
                                </div>
                                <!-- Top players show -->
                                <input type="number" name="topShow" class="form-control"
                                       placeholder="<?= LangManager::translate("votes.dashboard.config.placeholder.top_show") ?>"
                                       value="<?= $config->getTopShow() ?>" required>
                            </div>

                            <!-- Reset -->
                            <div class="form-group">
                                <label><?= LangManager::translate("votes.dashboard.config.placeholder.reset") ?></label>
                                <select name="reset" class="form-control" required>
                                    <option value="<?= $config->getReset() ?>" selected>
                                        <?= ($config->getReset() === 0) ? LangManager::translate("votes.dashboard.config.reset.0") :
                                            LangManager::translate("votes.dashboard.config.reset.1") ?>
                                    </option>

                                    <option value="<?= ($config->getReset() === 1) ? 0 : 1 ?>">
                                        <?= ($config->getReset() === 1) ? LangManager::translate("votes.dashboard.config.reset.0") :
                                            LangManager::translate("votes.dashboard.config.reset.1") ?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= LangManager::translate("votes.dashboard.config.placeholder.enable_api") ?></label>
                                <select name="api" class="form-control" required>
                                    <option value="1" <?= $config->isEnableApi() ? "selected" : "" ?>>
                                        <?= LangManager::translate("votes.dashboard.config.enable_api.1") ?>
                                    </option>

                                    <option value="0" <?= !$config->isEnableApi() ? "selected" : "" ?>>
                                        <?= LangManager::translate("votes.dashboard.config.enable_api.0") ?></option>
                                </select>
                            </div>
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
                        <div class="card-footer">


                            <button type="submit" class="btn btn-primary float-right">
                                <?= LangManager::translate("core.btn.save") ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>