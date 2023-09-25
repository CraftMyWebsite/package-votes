<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

$title = LangManager::translate("votes.dashboard.title.list_sites");
$description = LangManager::translate("votes.dashboard.desc");

/** @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
/** @var \CMW\Entity\Votes\VotesSitesEntity[] $sites */

/* @var array $compatiblesSites */

?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-sliders"></i> <span
            class="m-lg-auto"><?= LangManager::translate("votes.dashboard.title.manage_site") ?></span></h3>
</div>


<section class="row">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.title.add_site") ?></h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php (new SecurityManager())->insertHiddenToken() ?>
                    <h6><?= LangManager::translate("votes.dashboard.add_site.input.title") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="text" class="form-control" name="title" value="" required
                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.title") ?>">
                        <div class="form-control-icon">
                            <i class="fas fa-heading"></i>
                        </div>
                    </div>
                    <h6><?= LangManager::translate("votes.dashboard.add_site.input.time") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="number" class="form-control" name="time" value="" required
                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.time") ?>">
                        <div class="form-control-icon">
                            <i class="fas fa-hourglass-start"></i>
                        </div>
                    </div>
                    <h6><?= LangManager::translate("votes.dashboard.add_site.input.url") ?> :</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="url" class="form-control" name="url" id="url" value="" required
                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.url") ?>">
                        <div class="form-control-icon">
                            <i class="fas fa-link"></i>
                        </div>
                    </div>
                    <h6><?= LangManager::translate("votes.dashboard.add_site.input.id_unique") ?> :</h6>
                    <div class="input-group mb-3">
                        <input type="text" name="idUnique" id="idUnique" class="form-control"
                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.id_unique") ?>">
                        <button class="btn btn-success" type="button" id="button-addon1" onclick="testId()">
                            <?= LangManager::translate("votes.dashboard.add_site.btn.testid") ?>
                        </button>
                    </div>

                    <h6>
                        <a href="<?= EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'cmw-admin/votes/rewards' ?>">
                            <i data-bs-toggle="tooltip"
                               title="<?= LangManager::translate('votes.dashboard.list_sites.tooltip.rewards') ?>"
                               class="fa-sharp fa-solid fa-circle-question"></i>
                        </a>
                        <?= LangManager::translate("votes.dashboard.add_site.input.rewards") ?> :
                    </h6>
                    <div class="form-group position-relative">
                        <select name="reward" class="form-control" required>
                            <option <?= is_null($rewards) ? 'selected' : '' ?> value="0">
                                <?= LangManager::translate("votes.dashboard.list_sites.noreward") ?>
                            </option>
                            <?php foreach ($rewards as $reward) : ?>
                                <option value="<?= $reward?->getRewardsId() ?>"><?= $reward->getTitle() ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="text-center">
                        <a type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                           data-bs-target="#sitecompatible">
                            <?= LangManager::translate("votes.dashboard.add_site.btn.sitescomp") ?>
                        </a>
                        <button type="submit" class="btn btn-primary float-right">
                            <?= LangManager::translate("core.btn.add") ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.title.list_sites") ?></h4>
            </div>
            <div class="card-body">
                <table class="table" id="table1">
                    <thead>
                    <tr>
                        <th class="text-center"><?= LangManager::translate("votes.dashboard.table.name") ?></th>
                        <th class="text-center"><?= LangManager::translate("votes.dashboard.table.time") ?></th>
                        <th class="text-center"><?= LangManager::translate("votes.dashboard.table.url") ?></th>
                        <th class="text-center"><?= LangManager::translate("votes.dashboard.table.reward") ?></th>
                        <th class="text-center"><?= LangManager::translate("votes.dashboard.table.action") ?></th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php $i = 1;
                    foreach ($sites as $site) : ?>
                        <tr>
                            <td><?= $site->getTitle() ?></td>
                            <td><?= $site->getTime() ?> <?= LangManager::translate("votes.dashboard.table.min") ?></td>
                            <td><?= mb_strimwidth($site->getUrl(), 0, 35, '...') ?></td>
                            <td><?= $site->getRewards()?->getTitle() ?></td>
                            <td>
                                <a type="button" data-bs-toggle="modal"
                                   data-bs-target="#edit-<?= $site->getSiteId() ?>">
                                    <i class="text-primary me-3 fas fa-edit"></i>
                                </a>
                                <a type="button" data-bs-toggle="modal"
                                   data-bs-target="#delete-<?= $site->getSiteId() ?>">
                                    <i class="text-danger fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- MODAL edit  -->

                        <div class="modal modal-lg fade text-left" id="edit-<?= $site->getSiteId() ?>" tabindex="-1"
                             role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title white"
                                            id="myModalLabel160"><?= LangManager::translate("votes.dashboard.modal.editing") ?> <?= $site->getTitle() ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="serveredit-<?= $site->getSiteId() ?>" method="post"
                                              action="<?= EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'cmw-admin/votes/site/edit' ?>">
                                            <?php (new SecurityManager())->insertHiddenToken() ?>
                                            <input type="text" name="siteId" value="<?= $site->getSiteId() ?>" hidden>
                                            <h6><?= LangManager::translate("votes.dashboard.add_site.input.title") ?>
                                                :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" name="title"
                                                       value="<?= $site->getTitle() ?>" required autocomplete="off"
                                                       placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.title") ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-heading"></i>
                                                </div>
                                            </div>
                                            <h6><?= LangManager::translate("votes.dashboard.add_site.input.time") ?>
                                                :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="number" class="form-control" name="time"
                                                       value="<?= $site->getTime() ?>" required autocomplete="off"
                                                       placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.time") ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-hourglass-start"></i>
                                                </div>
                                            </div>
                                            <h6><?= LangManager::translate("votes.dashboard.add_site.input.url") ?>
                                                :</h6>
                                            <div class="form-group position-relative has-icon-left">
                                                <input type="url" class="form-control" name="url"
                                                       id="urlEdit-<?= $site->getSiteId() ?>"
                                                       value="<?= $site->getUrl() ?>" required autocomplete="off"
                                                       placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.url") ?>">
                                                <div class="form-control-icon">
                                                    <i class="fas fa-link"></i>
                                                </div>
                                            </div>
                                            <h6><?= LangManager::translate("votes.dashboard.add_site.input.id_unique") ?>
                                                :</h6>
                                            <div class="input-group mb-3">
                                                <input type="text" name="idUnique"
                                                       id="idUniqueEdit-<?= $site->getSiteId() ?>"
                                                       value="<?= $site->getIdUnique() ?>" required class="form-control"
                                                       placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.id_unique") ?>">
                                                <button class="btn btn-success" type="button" id="button-addon1"
                                                        onclick="testId(<?= $site->getSiteId() ?>)">
                                                    <?= LangManager::translate("votes.dashboard.add_site.btn.testid") ?>
                                                </button>
                                            </div>

                                            <h6><?= LangManager::translate("votes.dashboard.add_site.input.rewards") ?>
                                                :</h6>
                                            <div class="form-group position-relative">
                                                <select name="reward" class="form-control">
                                                    <option <?= $site->getRewards() === NULL ? 'selected' : '' ?>
                                                        value="0">
                                                        <?= LangManager::translate("votes.dashboard.list_sites.noreward") ?>
                                                    </option>

                                                    <!-- Get all rewards -->
                                                    <?php foreach ($rewards as $reward) : ?>
                                                        <option
                                                            value="<?= $reward?->getRewardsId() ?>" <?= ($site?->getRewards()?->getRewardsId() === $reward?->getRewardsId() ? "selected" : "") ?>><?= $reward->getTitle() ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span
                                                class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                        </button>
                                        <button type="submit" form="serveredit-<?= $site->getSiteId() ?>"
                                                class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span
                                                class="d-none d-sm-block"><?= LangManager::translate("core.btn.save") ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL delete  -->

                        <div class="modal fade text-left" id="delete-<?= $site->getSiteId() ?>" tabindex="-1"
                             role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title white"
                                            id="myModalLabel160"><?= LangManager::translate("votes.dashboard.modal.delete") ?> <?= $site->getTitle() ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        <?= LangManager::translate("votes.dashboard.modal.deletealert") ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span
                                                class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                        </button>
                                        <a href="delete/<?= $site->getSiteId() ?>" class="btn btn-danger ml-1">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span
                                                class="d-none d-sm-block"><?= LangManager::translate("core.btn.delete") ?></span>
                                        </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ++$i; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- MODAL SiteCompatibles -->

<div class="modal modal-lg fade text-left" id="sitecompatible" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white"
                    id="myModalLabel160"><?= LangManager::translate("votes.dashboard.add_site.sitescomp.modal_title") ?></h5>
            </div>
            <div class="modal-body">
                <?php foreach ($compatiblesSites as $site => $url): ?>
                    <li><a class="text-white" href="<?= $url ?>" target="_blank"><?= $url ?></a></li>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <a href="https://github.com/CraftMyWebsite/package-votes/issues/new?assignees=&labels=&template=ajouts-d-un-site-de-vote.md&title=%5BNEW+WEBSITE%5D"
                   class="btn btn-primary"
                   target="_blank"><?= LangManager::translate("votes.dashboard.add_site.sitescomp.request") ?>
                </a>
                <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                </button>
            </div>
        </div>
    </div>
</div>                               
