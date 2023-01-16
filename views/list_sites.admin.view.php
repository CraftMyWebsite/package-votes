<?php

use CMW\Manager\Lang\LangManager;
use CMW\Utils\SecurityService;

$title = LangManager::translate("votes.dashboard.title.list_sites");
$description = LangManager::translate("votes.dashboard.desc");

/** @var \CMW\Entity\Votes\VotesRewardsEntity[] $rewards */
/** @var \CMW\Entity\Votes\VotesSitesEntity[] $sites */

?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-sliders"></i> <span class="m-lg-auto"><?= LangManager::translate("votes.dashboard.title.manage_site") ?></span></h3>
</div>


<section class="row">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4>Ajout d'un site</h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php (new SecurityService())->insertHiddenToken() ?>
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
                        <input type="text" name="idUnique" id="idUnique" class="form-control" placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.id_unique") ?>">
                        <button class="btn btn-success" type="button" id="button-addon1">
                            <?= LangManager::translate("votes.dashboard.add_site.btn.testid") ?>
                        </button>
                    </div>
                    
                    <h6><?= LangManager::translate("votes.dashboard.add_site.input.rewards") ?> :</h6>
                    <div class="form-group position-relative">
                       <select name="reward" class="form-control" required>
                            <?php foreach ($rewards as $reward) : ?>
                                <option value="<?= $reward?->getRewardsId() ?>"><?= $reward->getTitle() ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="text-center">
                        <a type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#sitecompatible">
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
                        <th class="text-center">Nom</th>
                        <th class="text-center">Temps de vote</th>
                        <th class="text-center">URL</th>
                        <th class="text-center">Id / API</th>
                        <th class="text-center">Récompenses</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php $i = 1; foreach ($sites as $site) : ?>
                        <tr>
                            <td><?= $site->getTitle() ?></td>
                            <td><?= $site->getTime() ?> minutes</td>
                            <td><?= mb_strimwidth($site->getUrl(), 0, 35, '...') ?></td>
                            <td><?= $site->getIdUnique() ?></td>
                            <td>Je sais pas faire</td>                         
                            <td>
                                <a type="button" data-bs-toggle="modal" data-bs-target="#edit-<?= $site->getSiteId() ?>">
                                    <i class="text-primary me-3 fas fa-edit"></i>
                                </a>
                                <a type="button" data-bs-toggle="modal" data-bs-target="#delete-<?= $site->getSiteId() ?>">
                                    <i class="text-danger fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <div class="modal modal-lg fade text-left" id="edit-<?= $site->getSiteId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title white" id="myModalLabel160">Edition de <?= $site->getTitle() ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        <form id="serveredit-<?= $site->getSiteId() ?>" method="post" action="">
                                            <?php (new SecurityService())->insertHiddenToken() ?>
                                            <input type="text" name="siteId" value="<?= $site->getSiteId() ?>"
                                                       hidden>
                                        <h6><?= LangManager::translate("votes.dashboard.add_site.input.title") ?> :</h6>
                                        <div class="form-group position-relative has-icon-left">
                                            <input type="text" class="form-control" name="title" value="<?= $site->getTitle() ?>" required autocomplete="off"
                                                   placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.title") ?>">
                                            <div class="form-control-icon">
                                                <i class="fas fa-heading"></i>
                                            </div>
                                        </div>
                                        <h6><?= LangManager::translate("votes.dashboard.add_site.input.time") ?> :</h6>
                                        <div class="form-group position-relative has-icon-left">
                                            <input type="number" class="form-control" name="time" value="<?= $site->getTime() ?>" required autocomplete="off"
                                                   placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.time") ?>">
                                            <div class="form-control-icon">
                                                <i class="fas fa-hourglass-start"></i>
                                            </div>
                                        </div>
                                        <h6><?= LangManager::translate("votes.dashboard.add_site.input.url") ?> :</h6>
                                        <div class="form-group position-relative has-icon-left">
                                            <input type="url" class="form-control" name="url" id="url" value="<?= $site->getUrl() ?>" required autocomplete="off"
                                                   placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.url") ?>">
                                            <div class="form-control-icon">
                                                <i class="fas fa-link"></i>
                                            </div>
                                        </div>
                                        <h6><?= LangManager::translate("votes.dashboard.add_site.input.id_unique") ?> :</h6>
                                        <div class="input-group mb-3">
                                            <input type="text" name="idUnique" id="idUnique" value="<?= $site->getIdUnique() ?>" required class="form-control" placeholder="<?= LangManager::translate("votes.dashboard.add_site.placeholder.id_unique") ?>">
                                            <button class="btn btn-success" type="button" id="button-addon1">
                                                <?= LangManager::translate("votes.dashboard.add_site.btn.testid") ?>
                                            </button>
                                        </div>
                                        
                                        <h6><?= LangManager::translate("votes.dashboard.add_site.input.rewards") ?> :</h6>
                                        <div class="form-group position-relative">
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
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                    </button>
                                    <button type="submit" form="serveredit-<?= $site->getSiteId() ?>" class="btn btn-success ml-1" data-bs-dismiss="modal">
                                        <i class="bx bx-check d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.save") ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade text-left" id="delete-<?= $site->getSiteId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title white" id="myModalLabel160">supression de <?= $site->getTitle() ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        La suppression du site de vote est définitive.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                                        </button>
                                        <a href="delete/<?= $site->getSiteId() ?>" class="btn btn-danger ml-1">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.delete") ?></span>
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
    Recompenses à recuperer et afficher dans le tableau</br>
Bouton supression fonctionne mais y'as un bug
    </div>
</div>
</section>

<div class="modal modal-lg fade text-left" id="sitecompatible" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("votes.dashboard.add_site.sitescomp.modal_title") ?></h5>
            </div>
            <div class="modal-body">
                <?php foreach ($compatiblesSites as $site => $url): ?>
                    <li><a href="<?= $url ?>" target="_blank"><?= $url ?></a></li>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <a href="https://github.com/CraftMyWebsite/package-votes/issues/new/choose" class="btn btn-primary" target="_blank"><?= LangManager::translate("votes.dashboard.add_site.sitescomp.request") ?>
                </a>
                <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block"><?= LangManager::translate("core.btn.close") ?></span>
                </button>
            </div>
        </div>
    </div>
</div>                               
