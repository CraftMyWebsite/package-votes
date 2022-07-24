<?php
$title = VOTES_DASHBOARD_TITLE_LISTE_SITES;
$description = VOTES_DASHBOARD_DESC;

/** @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
/** @var \CMW\Entity\Votes\VotesSitesEntity[] $sites */

?>

<div class="content">

    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= VOTES_DASHBOARD_LISTE_SITE_TITLE ?></h3>
                    </div>

                    <div class="card-body">

                        <div id="accordion">

                            <?php foreach ($sites as $site) : ?>
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100 collapsed" data-toggle="collapse"
                                               href="#collapse<?= $site->getSiteId() ?>" aria-expanded="false">
                                                <?= $site->getTitle() ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?= $site->getSiteId() ?>" class="collapse"
                                         data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <form action="" method="post">

                                                <input type="text" name="siteId" value="<?= $site->getSiteId() ?>"
                                                       hidden>

                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-heading"></i></span>
                                                    </div>
                                                    <input type="text" name="title" class="form-control"
                                                           placeholder="<?= VOTES_DASHBOARD_ADD_SITE_PLACEHOLDER_TITLE ?>"
                                                           value="<?= $site->getTitle() ?>"
                                                           required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-hourglass-start"></i></span>
                                                    </div>
                                                    <input type="number" name="time" class="form-control"
                                                           placeholder="<?= VOTES_DASHBOARD_ADD_SITE_PLACEHOLDER_TIME ?>"
                                                           value="<?= $site->getTime() ?>"
                                                           required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-link"></i></span>
                                                    </div>
                                                    <input type="url" name="url" id="url" class="form-control"
                                                           placeholder="<?= VOTES_DASHBOARD_ADD_SITE_PLACEHOLDER_URL ?>"
                                                           value="<?= $site->getUrl() ?>"
                                                           required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-fingerprint"></i></span>
                                                    </div>
                                                    <input type="text" name="idUnique" id="idUnique"
                                                           class="form-control"
                                                           placeholder="<?= VOTES_DASHBOARD_ADD_SITE_PLACEHOLDER_ID_UNIQUE ?>"
                                                           value="<?= $site->getIdUnique() ?>"
                                                           required>
                                                    <div class="input-group-prepend">
                                                        <button type="button" onclick="testId();"
                                                                class="btn btn-success"><?= VOTES_DASHBOARD_ADD_SITE_BTN_TESTID ?></button>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label><?= VOTES_DASHBOARD_ADD_SITE_PLACEHOLDER_REWARDS ?></label>
                                                    <select name="reward" class="form-control" required>
                                                        <!-- If the reward was delete we set a default placeholder -->
                                                        <?php if ($site->getRewards() === NULL): ?>
                                                            <option selected><?= VOTES_DASHBOARD_LIST_NOREWARD ?></option>
                                                        <?php endif; ?>

                                                        <!-- Get all rewards -->
                                                        <?php foreach ($rewards as $reward) : ?>
                                                            <option value="<?= $reward->getRewardsId() ?>" <?= ($site->getRewards()->getRewardsId() === $reward->getRewardsId() ? "selected" : "") ?>><?= $reward->getTitle() ?></option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                </div>


                                                <input type="submit" value="<?= VOTES_DASHBOARD_BTN_SAVE ?>"
                                                       class="btn btn-primary float-right">

                                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#sitesDel<?= $site->getSiteId() ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>


                                                <!-- Modal Delete verif -->
                                                <div class="modal fade" id="sitesDel<?= $site->getSiteId() ?>"
                                                     tabindex="-1" role="dialog"
                                                     aria-labelledby="siteDelLabel<?= $site->getSiteId() ?>"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="sitesCompLabel">
                                                                    <?= VOTES_DASHBOARD_LIST_DELSITE_MODAL_TITLE ?>
                                                                    <strong><?= $site->getTitle() ?></strong>
                                                                </h5>
                                                                <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <!-- Button for delete the website -->
                                                            <div class="modal-body">
                                                                <?= VOTES_DASHBOARD_LIST_DELSITE_MODAL_BODY ?>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <a href="delete/<?= $site->getSiteId() ?>"
                                                                   class="btn btn-danger">
                                                                    <?= VOTES_DASHBOARD_LIST_DELSITE_MODAL_BTN_DEL ?>
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