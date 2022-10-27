<?php

use CMW\Model\Users\UsersModel;

$title = "Mon-Serveur | Voter";
$description = "Votez pour le serveur et gagnez des récompenses uniques!";

/* @var \CMW\Entity\Votes\VotesSitesEntity[] $sites */
/* @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $topCurrent */
/* @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $topGlobal */
?>


<main role="main">
    <div class="container">
        <div class="content">

            <?php if (usersModel::getLoggedUser() === -1): ?>
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
                                        href="<?= getenv('PATH_SUBFOLDER') ?>login">ici</a>
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
                                    <div class="package__buttons package__buttons--outBasket">
                                        <a onclick="sendVote('<?= $site->getSiteId() ?>')"
                                           type="button" rel="noopener noreferrer"
                                           class="btn btn--primary cursorAura">Voter
                                        </a>
                                    </div>
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
                                        <td><?= $top->getUser()->getUsername() ?></td>
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
                                        <td><?= $top->getUser()->getUsername() ?></td>
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