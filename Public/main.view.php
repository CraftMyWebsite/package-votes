<?php

use CMW\Controller\Users\UsersController;
use CMW\Manager\Env\EnvManager;
use CMW\Model\Votes\VotesConfigModel;
use CMW\Utils\Website;

/* @var \CMW\Entity\Votes\VotesSitesEntity [] $sites */
/* @var \CMW\Entity\Votes\VotesPlayerStatsEntity [] $topCurrent */
/* @var \CMW\Entity\Votes\VotesPlayerStatsEntity [] $topGlobal */

Website::setTitle('Votez');
Website::setDescription("Votez, obtenez des points de vote et plein d'autres cadeaux!");
?>
<section style="width: 70%;padding-bottom: 6rem;margin: 1rem auto auto;">

<div style="display: flex; flex-wrap: wrap; gap: 1rem;">
    <div style="flex: 0 0 35%; border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px;">
        <h2>Participer</h2>
        <?php if (!UsersController::isUserLogged()): ?>
            <p>Pour pouvoir voter et récupérer vos récompenses vous devez être connecté sur le site, alors n'attendez plus pour obtenir des récompenses uniques !</p>
            <a style="text-align: center" href="<?= EnvManager::getInstance()->getValue('PATH_SUBFOLDER') ?>login" >Connexion</i></a>
        <?php else: ?>
            <!-- LIST SITES -->
            <?php foreach ($sites as $site): ?>
                <div style="border: solid 1px #ab9999; border-radius: 5px; padding: 9px;">
                    <div style="display: flex; justify-content: space-between">
                        <h5><?= $site->getTitle() ?></h5>
                        <div><?= $site->getTimeFormatted() ?></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center">
                        <div>Récompense : <span class="font-bold"><?= $site->getRewards()?->getTitle() ?></span></div>
                        <button id="<?= $site->getSiteId() ?>" onclick="sendVote('<?= $site->getSiteId() ?>')">Voter</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div style="flex: 0 0 63%; border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px;">
        <h2 style="text-align: center">Top <?= VotesConfigModel::getInstance()->getConfig()->getTopShow() ?> du mois</h2>
        <table style="width: 100%">
            <thead>
            <tr>
                <th scope="col">
                   Voteur
                </th>
                <th scope="col" style="text-align: center">
                    Position
                </th>
                <th scope="col" style="text-align: end">
                    Nombres de votes
                </th>
            </tr>
            </thead>
            <tbody>

            <?php $i = 0;
            foreach ($topCurrent as $top):
                $i++; ?>

                <tr>
                    <td scope="row" style="display: flex; align-items: center; gap: .7rem">
                        <img style="width: 32px" src="<?= $top->getUser()->getUserPicture()->getImage() ?>" alt="...">
                        <div style="padding: .4rem;"><?= $top->getUser()->getPseudo() ?></div>
                    </td>
                    <td style="text-align: center">
                        <?php $color_position = $i ?>
                        <div style="width: fit-content; padding: .2rem; margin: auto; background-color:
                                                <?php
                        switch ($color_position) {
                            case '1':
                                echo 'yellow';
                                break;
                            case '2':
                                echo '#b9b91f';
                                break;
                            case '3':
                                echo '#98981f';
                                break;
                            default:
                                echo '#4da2e7';
                                break;
                        }
                        ?>"># <?= $i ?></div>
                    </td>
                    <td style="text-align: end">
                        <b><?= $top->getVotes() ?></b>
                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px; margin-top: 20px">
    <h2 style="text-align: center">Top <?= VotesConfigModel::getInstance()->getConfig()->getTopShow() ?> global</h2>
    <table style="width: 100%">
        <thead>
        <tr>
            <th scope="col" class="py-3 px-6">
                <i class="fa-solid fa-user"></i> Voteur
            </th>
            <th scope="col"  style="text-align: center">
                <i class="fa-solid fa-trophy"></i> Position
            </th>
            <th scope="col"  style="text-align: end">
                <i class="fa-solid fa-award"></i> Nombres de votes
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 0;
        foreach ($topGlobal as $top):
            $i++; ?>
            <tr>
                <th scope="row" style="display: flex; align-items: center; gap: .7rem">
                    <img style="width: 32px" src="<?= $top->getUser()->getUserPicture()->getImage() ?>" alt="...">
                    <div style="padding: .4rem;" ><?= $top->getUser()->getPseudo() ?></div>
                </th>
                <td style="text-align: center">
                    <?php $color_position = $i ?>
                    <div style="width: fit-content; padding: .2rem; margin: auto; background-color:
                                                <?php
                    switch ($color_position) {
                        case '1':
                            echo 'yellow';
                            break;
                        case '2':
                            echo '#b9b91f';
                            break;
                        case '3':
                            echo '#98981f';
                            break;
                        default:
                            echo '#4da2e7';
                            break;
                    }
                    ?>"># <?= $i ?></div>
                </td>
                <td style="text-align: end">
                    <b><?= $top->getVotes() ?></b>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</section>