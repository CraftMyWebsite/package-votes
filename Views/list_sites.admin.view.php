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

<h3><i class="fa-solid fa-sliders"></i> <?= LangManager::translate("votes.dashboard.title.manage_site") ?></h3>

<div class="grid-3">
    <div class="card">
        <form method="post" action="">
            <?php (new SecurityManager())->insertHiddenToken() ?>
            <div class="space-y-4">
                <h6><?= LangManager::translate("votes.dashboard.title.add_site") ?></h6>
                <div>
                    <label for="reward"><?= LangManager::translate("votes.dashboard.add_site.input.rewards") ?> :</label>
                    <select name="reward" id="reward">
                        <option <?= is_null($rewards) ? 'selected' : '' ?> value="0">
                            <?= LangManager::translate("votes.dashboard.list_sites.noreward") ?>
                        </option>
                        <?php foreach ($rewards as $reward) : ?>
                            <option value="<?= $reward?->getRewardsId() ?>"><?= $reward->getTitle() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="title"><?= LangManager::translate("votes.dashboard.add_site.input.title") ?> :</label>
                    <div class="input-group">
                        <i class="fa-solid fa-heading"></i>
                        <input type="text" id="title" name="title" value="" required
                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.title") ?>">
                    </div>
                </div>
                <div>
                    <label for="time"><?= LangManager::translate("votes.dashboard.add_site.input.time") ?> :</label>
                    <div class="input-group">
                        <i class="fa-solid fa-hourglass-start"></i>
                        <input type="number" id="time" name="time" value="" required
                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.time") ?>">
                    </div>
                </div>
                <div>
                    <label for="url"><?= LangManager::translate("votes.dashboard.add_site.input.url") ?> :</label>
                    <div class="input-group">
                        <i class="fa-solid fa-link"></i>
                        <input type="url" name="url" id="url" value="" required
                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.url") ?>">
                    </div>
                </div>
                <div>
                    <label for="idUnique"><?= LangManager::translate("votes.dashboard.add_site.input.id_unique") ?> :</label>
                    <div class="input-btn">
                        <input type="tel" name="idUnique" id="idUnique"
                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.id_unique") ?>">
                        <button type="button" id="button-addon1" onclick="testId()">
                            <?= LangManager::translate("votes.dashboard.add_site.btn.testid") ?></button>
                    </div>
                </div>
                <div class="lg:flex justify-center space-x-2">
                    <button data-modal-toggle="modal-site" class="btn-success" type="button"><?= LangManager::translate("votes.dashboard.add_site.btn.sitescomp") ?></button>
                    <button type="submit" class="btn-primary">
                        <?= LangManager::translate("core.btn.add") ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card col-span-2">
        <h6><?= LangManager::translate("votes.dashboard.title.list_sites") ?></h6>
        <div class="table-container table-container-striped">
            <table class="table" id="table1">
                <thead>
                <tr>
                    <th><?= LangManager::translate("votes.dashboard.table.name") ?></th>
                    <th><?= LangManager::translate("votes.dashboard.table.time") ?></th>
                    <th><?= LangManager::translate("votes.dashboard.table.url") ?></th>
                    <th><?= LangManager::translate("votes.dashboard.table.reward") ?></th>
                    <th class="text-center"><?= LangManager::translate("votes.dashboard.table.action") ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($sites as $site) : ?>
                    <tr>
                        <td><?= $site->getTitle() ?></td>
                        <td><?= $site->getTime() ?> <?= LangManager::translate("votes.dashboard.table.min") ?></td>
                        <td><a class="link" target="_blank" href="<?= $site->getUrl() ?>"><?= mb_strimwidth($site->getUrl(), 0, 35, '...') ?></a></td>
                        <td><?= $site->getRewards()?->getTitle() ?></td>
                        <td class="text-center space-x-2">
                            <button data-modal-toggle="modal-edit-<?= $site->getSiteId() ?>" type="button"><i class="text-info fas fa-edit"></i></button>
                            <button data-modal-toggle="modal-delete-<?= $site->getSiteId() ?>" type="button"><i class="text-danger fas fa-trash-alt"></i></button>
                            <div id="modal-delete-<?= $site->getSiteId() ?>" class="modal-container">
                                <div class="modal">
                                    <div class="modal-header-danger">
                                        <h6><?= LangManager::translate("votes.dashboard.modal.delete") ?> <?= $site->getTitle() ?></h6>
                                        <button type="button" data-modal-hide="modal-delete-<?= $site->getSiteId() ?>"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <?= LangManager::translate("votes.dashboard.modal.deletealert") ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="delete/<?= $site->getSiteId() ?>" class="btn-danger">
                                            <?= LangManager::translate("core.btn.delete") ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div id="modal-edit-<?= $site->getSiteId() ?>" class="modal-container">
                                <div class="modal">
                                    <div class="modal-header">
                                        <h6><?= LangManager::translate("votes.dashboard.modal.editing") ?> <?= $site->getTitle() ?></h6>
                                        <button type="button" data-modal-hide="modal-edit-<?= $site->getSiteId() ?>"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <form method="post" action="<?= EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'cmw-admin/votes/site/edit' ?>">
                                        <?php (new SecurityManager())->insertHiddenToken() ?>
                                        <div class="modal-body">
                                            <input type="text" name="siteId" value="<?= $site->getSiteId() ?>" hidden>
                                            <div class="space-y-4" style="text-align: left">
                                                <div>
                                                    <label for="title"><?= LangManager::translate("votes.dashboard.add_site.input.title") ?> :</label>
                                                    <div class="input-group">
                                                        <i class="fa-solid fa-heading"></i>
                                                        <input type="text" id="title" name="title" value="<?= $site->getTitle() ?>" required
                                                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.title") ?>">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="time"><?= LangManager::translate("votes.dashboard.add_site.input.time") ?> :</label>
                                                    <div class="input-group">
                                                        <i class="fa-solid fa-hourglass-start"></i>
                                                        <input type="number" id="time" name="time" value="<?= $site->getTime() ?>" required
                                                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.time") ?>">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="urlEdit-<?= $site->getSiteId() ?>"><?= LangManager::translate("votes.dashboard.add_site.input.url") ?> :</label>
                                                    <div class="input-group">
                                                        <i class="fa-solid fa-link"></i>
                                                        <input type="url" name="url" id="urlEdit-<?= $site->getSiteId() ?>"
                                                               value="<?= $site->getUrl() ?>" required
                                                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.url") ?>">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="idUniqueEdit-<?= $site->getSiteId() ?>"><?= LangManager::translate("votes.dashboard.add_site.input.id_unique") ?> :</label>
                                                    <div class="input-btn">
                                                        <input type="tel" name="idUnique" id="idUniqueEdit-<?= $site->getSiteId() ?>"
                                                               value="<?= $site->getIdUnique() ?>"
                                                               placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.id_unique") ?>">
                                                        <button type="button" id="button-addon1" onclick="testId(<?= $site->getSiteId() ?>)">
                                                            <?= LangManager::translate("votes.dashboard.add_site.btn.testid") ?></button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="reward"><?= LangManager::translate("votes.dashboard.add_site.input.rewards") ?> :</label>
                                                    <select name="reward" id="reward">
                                                        <option <?= $site->getRewards() === NULL ? 'selected' : '' ?>
                                                            value="0">
                                                            <?= LangManager::translate("votes.dashboard.list_sites.noreward") ?>
                                                        </option>
                                                        <?php foreach ($rewards as $reward) : ?>
                                                            <option
                                                                value="<?= $reward?->getRewardsId() ?>" <?= ($site?->getRewards()?->getRewardsId() === $reward?->getRewardsId() ? "selected" : "") ?>><?= $reward->getTitle() ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn-primary">
                                                <?= LangManager::translate("core.btn.edit") ?>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php ++$i; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- MODAL SiteCompatibles -->
<div id="modal-site" class="modal-container">
    <div class="modal">
        <div class="modal-header">
            <h6><?= LangManager::translate("votes.dashboard.add_site.sitescomp.modal_title") ?></h6>
            <button type="button" data-modal-hide="modal-site"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <?php foreach ($compatiblesSites as $site => $url): ?>
                <li><a class="link" href="<?= $url ?>" target="_blank"><?= $url ?></a></li>
            <?php endforeach; ?>
        </div>
        <div class="modal-footer">
            <a href="https://github.com/CraftMyWebsite/package-votes/issues/new?assignees=&labels=&template=ajouts-d-un-site-de-vote.md&title=%5BNEW+WEBSITE%5D"
               class="btn-primary"
               target="_blank"><?= LangManager::translate("votes.dashboard.add_site.sitescomp.request") ?>
            </a>
        </div>
    </div>
</div>
