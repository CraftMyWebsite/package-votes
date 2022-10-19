<?php

use CMW\Manager\Lang\LangManager;
use CMW\Utils\SecurityService;

$title = LangManager::translate("votes.dashboard.title.rewards");
$description = LangManager::translate("votes.dashboard.desc");

/* @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
?>

<div class="content">

    <div class="container-fluid">
        <div class="row">

            <!-- Add new rewards -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= LangManager::translate("votes.dashboard.title.rewards") ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="rewards/add" method="post">
                            <?php (new SecurityService())->insertHiddenToken() ?>
                            <div class="form-group">
                                <label><?= LangManager::translate("votes.dashboard.rewards.add.title") ?></label>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text" name="title" class="form-control"
                                       placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?>"
                                       required>
                            </div>

                            <label><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.type") ?></label>
                            <select id="reward_type" name="reward_type" class="form-control" required>
                                <option value="none"
                                        selected><?= LangManager::translate("votes.dashboard.rewards.add.placeholder.type_select") ?></option>
                                <option value="votepoints"><?= LangManager::translate("votes.dashboard.rewards.votepoints.name") ?></option>
                                <option value="votepoints-random">
                                    <?= LangManager::translate("votes.dashboard.rewards.votepoints.name") ?>
                                    <?= LangManager::translate("votes.dashboard.rewards.votepoints.random") ?>
                                </option>
                            </select>

                            <!-- JS container -->
                            <div id="reward-content-wrapper" class="mt-3"></div>


                            <input type="submit" value="<?= LangManager::translate("core.btn.save") ?>"
                                   class="btn btn-primary float-right" id="reward-type-btn-save" disabled>
                        </form>
                    </div>
                </div>

            </div>

            <!-- List rewards -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= LangManager::translate("votes.dashboard.rewards.list.title") ?></h3>
                    </div>

                    <div class="card-body">

                        <div id="accordion">

                            <?php foreach ($rewards as $reward) : ?>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100 collapsed" data-toggle="collapse"
                                               href="#collapse<?= $reward->getRewardsId() ?>" aria-expanded="false">
                                                <?= $reward->getTitle() ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?= $reward->getRewardsId() ?>" class="collapse"
                                         data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <form action="" method="post">
                                                <?php (new SecurityService())->insertHiddenToken() ?>
                                                <!-- Faire une requête ajax pour récupérer l'action -->
                                                <input type="hidden" value="<?= "'" . $reward->getAction() . "'" ?>">

                                                <input type="text" name="reward_id"
                                                       value="<?= $reward->getRewardsId() ?>"
                                                       hidden>

                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                    class="fas fa-heading"></i></span>
                                                    </div>
                                                    <input type="text" name="title" class="form-control"
                                                           placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.title") ?>"
                                                           value="<?= $reward->getTitle() ?>"
                                                           required>
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

                                                        <option value="votepoints-random" <?= json_decode($reward->getAction())->type === "votepoints-random" ? "selected" : "" ?>>
                                                            <?= LangManager::translate("votes.dashboard.rewards.votepoints.name") ?>
                                                            <?= LangManager::translate("votes.dashboard.rewards.votepoints.random") ?>
                                                        </option>

                                                    </select>
                                                </div>

                                                <!-- JS container (auto generate with php, and update with reward.js) -->
                                                <div id="reward-content-wrapper-update-<?= $reward->getRewardsId() ?>"
                                                     class="mt-3">
                                                    <?php if (json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->type === "votepoints"): ?>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                            class="fas fa-coins"></i></span>
                                                            </div>

                                                            <input value="<?= json_decode($reward->getAction(), false, 512, JSON_THROW_ON_ERROR)->amount ?>"
                                                                   placeholder="<?= LangManager::translate("votes.dashboard.rewards.add.placeholder.amount") ?>>"
                                                                   type="number" name="amount"
                                                                   class="form-control" required>

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

                                                    <?php endif; ?>
                                                </div>


                                                <input type="submit"
                                                       value="<?= LangManager::translate("core.btn.save") ?>"
                                                       class="btn btn-primary float-right">

                                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#rewardDel<?= $reward->getRewardsId() ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>


                                                <!-- Modal Delete verif -->
                                                <div class="modal fade" id="rewardDel<?= $reward->getRewardsId() ?>"
                                                     tabindex="-1" role="dialog"
                                                     aria-labelledby="rewardDelLabel<?= $reward->getRewardsId() ?>"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="rewardCompLabel">
                                                                    <?= LangManager::translate("votes.dashboard.rewards.del.title") ?>
                                                                    <strong><?= $reward->getTitle() ?></strong>
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <!-- Button for delete the website -->
                                                            <div class="modal-body">
                                                                <?= LangManager::translate("votes.dashboard.rewards.del.body") ?>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <a href="rewards/delete/<?= $reward->getRewardsId() ?>"
                                                                   class="btn btn-danger">
                                                                    <?= LangManager::translate("core.btn.delete_forever") ?>
                                                                </a>
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal"><?= LangManager::translate("core.btn.close") ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
