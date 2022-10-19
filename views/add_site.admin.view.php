<?php
header('Access-Control-Allow-Origin: *');

use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Utils\SecurityService;

$title = LangManager::translate("votes.dashboard.title.add_site");
$description = LangManager::translate("votes.dashboard.desc");

/** @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */

?>



<div class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <form action="" method="post">
                    <?php (new SecurityService())->insertHiddenToken() ?>
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?= LangManager::translate("votes.dashboard.add_site.card_title") ?> :</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text" name="title" class="form-control"
                                       placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.title") ?>" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-hourglass-start"></i></span>
                                </div>
                                <input type="number" name="time" class="form-control"
                                       placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.time") ?>" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                </div>
                                <input type="url" name="url" id="url" class="form-control"
                                       placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.url") ?>" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                </div>
                                <input type="text" name="idUnique" id="idUnique" class="form-control"
                                       placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.id_unique") ?>" required>
                                <div class="input-group-prepend">
                                    <button type="button" onclick="testId();" class="btn btn-success"><?= LangManager::translate("votes.dashboard.add_site.btn.testid") ?></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reward"><?= LangManager::translate("votes.dashboard.add_site.placeholder.rewards") ?></label>
                                <select name="reward" class="form-control" required>
                                    <?php foreach ($rewards as $reward) : ?>
                                        <option value="<?= $reward?->getRewardsId() ?>"><?=$reward->getTitle()?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sitesComp">
                                <?= LangManager::translate("votes.dashboard.add_site.btn.sitescomp") ?>
                            </button>

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


<!-- Modal Listing websites compatibilities -->
<div class="modal fade" id="sitesComp" tabindex="-1" role="dialog" aria-labelledby="sitesCompLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sitesCompLabel"><?= LangManager::translate("votes.dashboard.add_site.sitescomp.modal_title") ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Websites name, exemple → serveurs-minecraft.org -->
            <div class="modal-body">
                <ul>
                    <!-- Minecraft server list -->
                    <h4 class="text-center font-weight-bold"><?= LangManager::translate("votes.dashboard.add_site.sitescomp.websites_title") ?></h4>
                    <hr>
                    <li><a href="https://serveur-prive.net" target="_blank">serveur-prive.net</a></li>
                    <li><a href="https://serveur-minecraft-vote.fr" target="_blank">serveur-minecraft-vote.fr</a></li>
                    <li><a href="https://serveurs-mc.net" target="_blank">serveurs-mc.net</a></li>
                    <li><a href="https://top-serveurs.net" target="_blank">top-serveurs.net</a></li>
                </ul>
            </div>

            <div class="modal-footer">

                <a href="https://github.com/CraftMyWebsite/package-votes/issues/new/choose" class="btn btn-primary"
                   target="_blank"><?= LangManager::translate("votes.dashboard.add_site.sitescomp.request") ?>
                </a>

                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= LangManager::translate("core.btn.close") ?></button>
            </div>
        </div>
    </div>
</div>