<?php

use CMW\Manager\Lang\LangManager;

$title = LangManager::translate("votes.dashboard.title.list_sites");
$description = LangManager::translate("votes.dashboard.desc");

/** @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
/** @var \CMW\Entity\Votes\VotesSitesEntity[] $sites */

?>

<div class="content">

    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= LangManager::translate("votes.dashboard.list_sites.title") ?></h3>
                    </div>

                    <div class="card-body">

                        <div id="accordion">

                            <?php $i = 1; foreach ($sites as $site) : ?>
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
                                                           placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.title") ?>"
                                                           value="<?= $site->getTitle() ?>"
                                                           required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-hourglass-start"></i></span>
                                                    </div>
                                                    <input type="number" name="time" class="form-control"
                                                           placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.tim") ?>"
                                                           value="<?= $site->getTime() ?>"
                                                           required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-link"></i></span>
                                                    </div>
                                                    <input type="url" name="url" id="url-<?= $i ?>" class="form-control"
                                                           placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.url") ?>"
                                                           value="<?= $site->getUrl() ?>"
                                                           required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="fas fa-fingerprint"></i></span>
                                                    </div>
                                                    <input type="text" name="idUnique" id="idUnique-<?= $i ?>"
                                                           class="form-control"
                                                           placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.id_unique") ?>"
                                                           value="<?= $site->getIdUnique() ?>"
                                                           required>
                                                    <div class="input-group-prepend">
                                                        <button type="button" onclick="testId(<?= $i ?>);"
                                                                class="btn btn-success"><?= LangManager::translate("votes.dashboard.add_site.btn.testid") ?></button>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label><?= LangManager::translate("votes.dashboard.add_site.placeholder.rewards") ?></label>
                                                    <select name="reward" class="form-control" required>
                                                        <!-- If the reward was delete we set a default placeholder -->
                                                        <?php if ($site->getRewards() === NULL): ?>
                                                            <option selected><?= LangManager::translate("votes.dashboard.list_sites.noreward") ?></option>
                                                        <?php endif; ?>

                                                        <!-- Get all rewards -->
                                                        <?php foreach ($rewards as $reward) : ?>
                                                            <option value="<?= $reward?->getRewardsId() ?>" <?= ($site?->getRewards()?->getRewardsId() === $reward?->getRewardsId() ? "selected" : "") ?>><?= $reward->getTitle() ?></option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                </div>


                                                <input type="submit"
                                                       value="<?= LangManager::translate("core.btn.save") ?>"
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
                                                                    <?= LangManager::translate("votes.dashboard.list_sites.del_site.modal.title") ?>
                                                                    <strong><?= $site->getTitle() ?></strong>
                                                                </h5>
                                                                <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <!-- Button for delete the website -->
                                                            <div class="modal-body">
                                                                <?= LangManager::translate("votes.dashboard.list_sites.del_site.modal.body") ?>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <a href="delete/<?= $site->getSiteId() ?>"
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
                            <?php ++$i; endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>