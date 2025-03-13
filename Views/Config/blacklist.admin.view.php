<?php

use CMW\Entity\Users\UserEntity;
use CMW\Entity\Votes\Config\VotesConfigBlacklistEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Website;

Website::setTitle(LangManager::translate('votes.dashboard.config.blacklist.title'));
Website::setDescription(LangManager::translate('votes.dashboard.config.blacklist.description'));

/* @var UserEntity $users */
/* @var VotesConfigBlacklistEntity[] $blacklists */
?>

<h3><i class="fa-solid fa-list"></i> <?= LangManager::translate('votes.dashboard.config.blacklist.heading') ?></h3>


<div class="card mt-4">
    <form method="POST">
        <?php SecurityManager::getInstance()->insertHiddenToken(); ?>

        <label for='userId'>Utilisateur à ajouter</label>
        <select id='userId' name="userId" class='choices' required>
            <?php foreach ($users as $user): ?>
                <option value='<?= $user->getId() ?>'><?= $user->getPseudo() ?></option>
            <?php endforeach; ?>
        </select>

        <div class="text-center">
            <button type='submit' class='btn-primary'>
                <?= LangManager::translate('core.btn.add') ?>
            </button>
        </div>

    </form>
</div>

<div class="table-container mt-4">
    <table id="table1">
        <thead>
        <tr>
            <th>Pseudo</th>
            <th>Auteur</th>
            <th>Date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($blacklists as $blacklist): ?>
            <tr>
                <td><?= $blacklist->getUserPseudo() ?></td>
                <td><?= $blacklist->getAuthorPseudo() ?></td>
                <td><?= $blacklist->getCreatedAtFormatted() ?></td>
                <td>
                    <a href="blacklist/delete/<?= $blacklist->getUserId() ?>" class="btn-danger">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
