<?php

use CMW\Controller\Core\ThemeController;
use CMW\Controller\Users\UsersController;
use CMW\Manager\Env\EnvManager;

$title = "Mon-Serveur | Voter";
$description = "Votez pour le serveur et gagnez des récompenses uniques!";

/* @var \CMW\Entity\Votes\VotesSitesEntity[] $sites */
/* @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $topCurrent */
/* @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $topGlobal */
?>


<main role="main">
    <div class="container">
        <div class="content">

            <?php if (UsersController::isUserLogged()): ?>
                <!-- Si le joueur n'est pas connecté -->
                <div class="panel">
                    <div class="panel__heading">
                        <h1>Connectez-vous</h1>
                    </div>
                    <div class="panel__body">

                        <!-- Information -->
                        <div class="">
                            <p class="text-center">Pour pouvoir voter et donc récupérer vos récompenses vous devez être
                                connecté
                                sur le site, alors n'attendez plus pour obtenir des <strong>récompenses uniques</strong>
                                !

                                <br>

                                <strong>Connectez-vous</strong> dès maintenant en cliquant <a
                                    href="<?= EnvManager::getInstance()->getValue("PATH_SUBFOLDER") ?>login">ici</a>
                            </p>


                        </div>

                    </div>
                </div>

            <?php else: ?>

                <div class="panel">
                    <div class="panel__heading">
                        <h1>Votez</h1>
                    </div>
                    <div class="panel__body">

                        <div class="category category--list">
                            <!-- LIST SITES -->

                            <?php foreach ($sites as $site): ?>
                                <div class="package">
                                    <div class="package__info">
                                        <h3><a href="<?= $site->getUrl() ?>"
                                               target="_BLANK"><?= $site->getTitle() ?></a></h3>
                                        <div class="package__tags">
                                            <span class="tag tag--left tag--700">1 à 3 VotePoints</span>
                                            <span class="tag tag--danger"><i
                                                    class="fas fa-stopwatch"></i><?= $site->getTimeFormatted() ?></span>
                                        </div>
                                    </div>
                                    <?php if ($site->isAvailable()): ?>
                                        <div>
                                            <a onclick="sendVote('<?= $site->getSiteId() ?>', this)"
                                               type="button" rel="noopener noreferrer"
                                               class="btn btn--primary cursorAura">Voter
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div>
                                            <a class="btn btn--primary cursorAura">
                                                Nouveau vote dans <?= $site->getTimeRemainingFormatted() ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<section>
    <div class="container">
        <div class="content">

            <div class="panel">
                <div class="panel__heading">
                    <h1>Classement</h1>
                </div>
                <div class="panel__body">

                    <div class="panel__heading">
                        <h3>Top 10 du mois</h3>
                    </div>
                    <div class="category category--list">
                        <!-- TOP VOTES CE MOIS-CI -->

                        <div class="table-wrapper">
                            <table class="fl-table">
                                <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Pseudo</th>
                                    <th>Votes</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php $i = 0;
                                foreach ($topCurrent as $top): $i++; ?>

                                    <tr>
                                        <td>#<?= $i ?></td>
                                        <td><?= $top->getUser()->getPseudo() ?></td>
                                        <td><?= $top->getVotes() ?></td>
                                    </tr>

                                <?php endforeach; ?>


                                <tbody>
                            </table>
                        </div>

                    </div>

                    <div class="panel__heading">
                        <h3>Top 10 global</h3>
                    </div>

                    <div class="category category--list">
                        <!-- TOP VOTES TOTAUX -->

                        <div class="table-wrapper">
                            <table class="fl-table">
                                <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Pseudo</th>
                                    <th>Votes</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php $i = 0;
                                foreach ($topGlobal as $top): $i++; ?>

                                    <tr>
                                        <td>#<?= $i ?></td>
                                        <td><?= $top->getUser()->getPseudo() ?></td>
                                        <td><?= $top->getVotes() ?></td>
                                    </tr>

                                <?php endforeach; ?>


                                <tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
</section>
<link rel="stylesheet"
      href="<?= EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'Admin/Resources/Vendors/Izitoast/iziToast.min.css' ?>">
<script
    src="<?= EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'Admin/Resources/Vendors/Izitoast/iziToast.min.js' ?>"></script>
<script
    src="<?= EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'App/Package/Votes/Views/Resources/Js/VotesStatus.js' ?>"></script>
<script src="<?= ThemeController::getCurrentTheme()->getPath() . 'Views/Votes/Resources/Js/VotesLogic.js' ?>"></script>