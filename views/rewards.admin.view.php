<?php
$title = VOTES_DASHBOARD_TITLE_REWARDS;
$description = VOTES_DASHBOARD_DESC;

/* @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
?>

<div class="content">

    <div class="container-fluid">
        <div class="row">

            <!-- Add new rewards -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= VOTES_DASHBOARD_ADD_REWARD_TITLE ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="rewards/add" method="post">
                            <div class="form-group">
                                <label><?= VOTES_DASHBOARD_ADD_SITE_PLACEHOLDER_REWARDS ?></label>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text" name="title" class="form-control"
                                       placeholder="<?= VOTES_DASHBOARD_ADD_REWARD_PLACEHOLDER_TITLE ?>"
                                       required>
                            </div>

                            <label><?= VOTES_DASHBOARD_ADD_REWARD_PLACEHOLDER_TYPE ?></label>
                            <select id="reward_type" name="reward_type" class="form-control" required>
                                <option value="none"
                                        selected><?= VOTES_DASHBOARD_ADD_REWARD_PLACEHOLDER_TYPE_SELECT ?></option>
                                <option value="votepoints"><?= VOTES_VOTEPOINTS_NAME ?></option>
                                <option value="votepoints-random"><?= VOTES_VOTEPOINTS_NAME ?> <?= VOTES_VOTEPOINTS_RANDOM ?></option>
                            </select>

                            <!-- JS container -->
                            <div id="reward-content-wrapper" class="mt-3"></div>


                            <input type="submit" value="<?= VOTES_DASHBOARD_BTN_SAVE ?>"
                                   class="btn btn-primary float-right" id="reward-type-btn-save" disabled>
                        </form>
                    </div>
                </div>

            </div>

            <!-- List rewards -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= VOTES_DASHBOARD_LISTE_REWARD_TITLE ?></h3>
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
                                                           placeholder="<?= VOTES_DASHBOARD_ADD_REWARD_PLACEHOLDER_TITLE ?>"
                                                           value="<?= $reward->getTitle() ?>"
                                                           required>
                                                </div>

                                                <div class="form-group">
                                                    <label><?= VOTES_DASHBOARD_ADD_SITE_PLACEHOLDER_REWARDS ?></label>
                                                    <select name="reward_type" class="form-control"
                                                            onchange="updateReward(this, <?= $reward->getRewardsId() ?>)"
                                                            required>

                                                        <option value="none" <?= json_decode($reward->getAction())->type === NULL ? "selected" : "" ?>>
                                                            <?= VOTES_DASHBOARD_ADD_REWARD_PLACEHOLDER_TYPE_SELECT ?>
                                                        </option>

                                                        <option value="votepoints" <?= json_decode($reward->getAction())->type === "votepoints" ? "selected" : "" ?>>
                                                            <?= VOTES_VOTEPOINTS_NAME ?>
                                                        </option>

                                                        <option value="votepoints-random" <?= json_decode($reward->getAction())->type === "votepoints-random" ? "selected" : "" ?>>
                                                            <?= VOTES_VOTEPOINTS_NAME ?> <?= VOTES_VOTEPOINTS_RANDOM ?>
                                                        </option>

                                                    </select>
                                                </div>

                                                <!-- JS container (auto generate with php, and update with reward.js) -->
                                                <div id="reward-content-wrapper-update-<?= $reward->getRewardsId() ?>"
                                                     class="mt-3">
                                                    <?php if (json_decode($reward->getAction())->type === "votepoints"): ?>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                            class="fas fa-coins"></i></span>
                                                            </div>

                                                            <input value="<?= json_decode($reward->getAction())->amount ?>"
                                                                   placeholder="Montant" type="number" name="amount"
                                                                   class="form-control" required>

                                                        </div>

                                                    <?php elseif (json_decode($reward->getAction())->type === "votepoints-random"): ?>

                                                        <div id="reward-content-wrapper" class="mt-3">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Montant minimum</label>
                                                                        <input placeholder="Montant minimum"
                                                                               value="<?= json_decode($reward['action'])->amount->min ?>"
                                                                               type="number" name="amount-min"
                                                                               class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Montant maximum</label>
                                                                        <input placeholder="Montant maximum"
                                                                               value="<?= json_decode($reward['action'])->amount->max ?>"
                                                                               type="number" name="amount-max"
                                                                               class="form-control" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php endif; ?>
                                                </div>


                                                <input type="submit" value="<?= VOTES_DASHBOARD_BTN_SAVE ?>"
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
                                                                    <?= VOTES_DASHBOARD_LIST_DELREWARD_MODAL_TITLE ?>
                                                                    <strong><?= $reward->getTitle() ?></strong>
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <!-- Button for delete the website -->
                                                            <div class="modal-body">
                                                                <?= VOTES_DASHBOARD_LIST_DELREWARD_MODAL_BODY ?>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <a href="rewards/delete/<?= $reward->getRewardsId() ?>"
                                                                   class="btn btn-danger">
                                                                    <?= VOTES_DASHBOARD_LIST_DELREWARD_MODAL_BTN_DEL ?>
                                                                </a>
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal"><?= VOTES_DASHBOARD_BTN_CLOSE ?></button>
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
