<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate("votes.dashboard.title.rewards");
$description = LangManager::translate("votes.dashboard.desc");

/* @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
/* @var \CMW\Entity\Minecraft\MinecraftServerEntity[] $minecraftServers */
?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-award"></i> <span
                class="m-lg-auto"><?= LangManager::translate("votes.dashboard.title.rewards") ?></span></h3>
</div>

<section class="row">
    <div class="col-12 col-lg-5">
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

                    <h6><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.type") ?> :</h6>
                    <div class="form-group position-relative">
                        <select id="reward_type" name="reward_type" class="form-control" required
                                onchange="handleSelectChange(event)">
                            <option value="none" selected>
                                <?= LangManager::translate("votes.dashboard.rewards.add.placeholder.type_select") ?>
                            </option>
                            <option value="votepoints">
                                <?= LangManager::translate("votes.dashboard.rewards.votepoints.name") ?>
                                <?= LangManager::translate("votes.dashboard.rewards.votepoints.fixed") ?>
                            </option>
                            <option value="votepoints-random">
                                <?= LangManager::translate("votes.dashboard.rewards.votepoints.name") ?>
                                <?= LangManager::translate("votes.dashboard.rewards.votepoints.random") ?>
                            </option>
                            <option value="minecraft-commands">
                                <?= LangManager::translate("votes.dashboard.rewards.minecraft.commands") ?>
                            </option>
                        </select>
                    </div>

                    <!-- JS container -->
                    <div id="reward-content-wrapper" class="mt-3"></div>

                    <div class="text-center">
                        <button disabled type="submit" id="reward-type-btn-save" class="btn btn-primary float-right">
                            <?= LangManager::translate("core.btn.add") ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
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
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php foreach ($rewards as $reward) : ?>
                        <tr>
                            <td><?= $reward->getTitle() ?></td>
                            <td>
                                <a type="button" data-bs-toggle="modal"
                                   data-bs-target="#edit-<?= $reward->getRewardsId() ?>">
                                    <i class="text-primary me-3 fas fa-edit"></i>
                                </a>
                                <a type="button" data-bs-toggle="modal"
                                   data-bs-target="#delete-<?= $reward->getRewardsId() ?>">
                                    <i class="text-danger fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <div class="modal modal-lg fade text-left" id="edit-<?= $reward->getRewardsId() ?>"
                             tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title white"
                                            id="myModalLabel160"><?= LangManager::translate("votes.dashboard.modal.editing") ?> <?= $reward->getTitle() ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="serveredit-<?= $reward->getRewardsId() ?>" method="post" action="">
                                            <?php (new SecurityManager())->insertHiddenToken() ?>
                                            <!-- Faire une requête ajax pour récupérer l'action -->
                                            <input type="hidden" value="<?= "'" . $reward->getAction() . "'" ?>">
                                            <input type="text" name="reward_id" value="<?= $reward->getRewardsId() ?>"
                                                   hidden>
                                            <h6><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?>
                                                :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" name="title" required
                                                       placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?>"
                                                       value="<?= $reward->getTitle() ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-heading"></i>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label><?= LangManager::translate("votes.dashboard.add_site.placeholder.rewards") ?></label>
                                                <select name="reward_type" class="form-control"
                                                        onchange="updateReward(this, <?= $reward->getRewardsId() ?>)"
                                                        required>
                                                    <option value="none" <?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->type === NULL ? "selected" : "" ?>>
                                                        <?= LangManager::translate("votes.dashboard.rewards.add.placeholder.type_select") ?>
                                                    </option>
                                                    <option value="votepoints" <?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->type === "votepoints" ? "selected" : "" ?>>
                                                        <?= LangManager::translate("votes.dashboard.rewards.votepoints.name") ?>
                                                    </option>
                                                    <option value="votepoints-random" <?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->type === "votepoints-random" ? "selected" : "" ?>>
                                                        <?= LangManager::translate("votes.dashboard.rewards.votepoints.name") ?>
                                                        <?= LangManager::translate("votes.dashboard.rewards.votepoints.random") ?>
                                                    </option>
                                                    <option value="minecraft-commands" <?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->type === "minecraft-commands" ? "selected" : "" ?>>
                                                        <?= LangManager::translate("votes.dashboard.rewards.minecraft.commands") ?>
                                                    </option>
                                                </select>
                                            </div>
                                            <!-- JS container (auto generate with php, and update with reward.js) -->
                                            <div id="reward-content-wrapper-update-<?= $reward->getRewardsId() ?>"
                                                 class="mt-3">
                                                <?php if (json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->type === "votepoints"): ?>
                                                    <div class="form-group position-relative has-icon-left">
                                                        <input value="<?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->amount ?>"
                                                               placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.amount") ?>"
                                                               type="number" name="amount" class="form-control"
                                                               required="true">
                                                        <div class="form-control-icon">
                                                            <i class="fas fa-coins"></i>
                                                        </div>
                                                    </div>
                                                <?php elseif (json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->type === "votepoints-random"): ?>
                                                    <div id="reward-content-wrapper" class="mt-3">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.amount_minimum") ?></label>
                                                                    <input placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.amount_minimum") ?>"
                                                                           value="<?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->amount->min ?>"
                                                                           type="number" name="amount-min"
                                                                           class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.amount_maximum") ?></label>
                                                                    <input placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.amount_maximum") ?>"
                                                                           value="<?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->amount->max ?>"
                                                                           type="number" name="amount-max"
                                                                           class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php elseif (json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->type === "minecraft-commands"): ?>
                                                    <div id="reward-content-wrapper" class="mt-3">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label><?= LangManager::translate("votes.dashboard.rewards.minecraft.commands") ?></label>
                                                                    <input value="<?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->commands ?>"
                                                                           placeholder="<?= LangManager::translate("votes.dashboard.rewards.minecraft.placeholder.commands") ?>"
                                                                           type="text" name="minecraft-commands"
                                                                           class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label><?= LangManager::translate("votes.dashboard.rewards.minecraft.servers") ?></label>
                                                                    <select name="minecraft-servers[]"
                                                                            class="form-control" required multiple>

                                                                        <?php foreach ($minecraftServers as $minecraftServer): ?>
                                                                            <option value="<?= $minecraftServer->getServerId() ?>"
                                                                                <?php foreach (json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->servers as $srvId) {
                                                                                    echo ((int)$srvId === $minecraftServer->getServerId()) ? "selected" : "";
                                                                                } ?>
                                                                            ><?= $minecraftServer->getServerName() ?>
                                                                            </option>
                                                                        <?php endforeach; ?>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                        </button>
                                        <button type="submit" form="serveredit-<?= $reward->getRewardsId() ?>"
                                                class="btn btn-success ml-1" data-bs-dismiss="modal">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.save") ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                        </button>
                                        <a href="rewards/delete/<?= $reward->getRewardsId() ?>"
                                           class="btn btn-danger ml-1">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.delete") ?></span>
                                        </a>
                                        </form>
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