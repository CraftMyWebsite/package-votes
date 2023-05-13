<?php

use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Manager\Lang\LangManager;

$title = LangManager::translate("votes.dashboard.title.stats");
$description = LangManager::translate("votes.dashboard.desc");


/* @var $stats */
/* @var $all */
/* @var $month */
/* @var $week */
/* @var $day */
/* @var VotesSitesEntity[] $listSites */
/* @var $previous3Months [] */
?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fas fa-chart-area"></i> <span
            class="m-lg-auto"><?= LangManager::translate("votes.dashboard.title.stats") ?></span></h3>
</div>

<section class="row">
    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.stats.somenumber") ?></h4>
            </div>
            <div class="card-body">
                <div class="alert alert-primary text-center">
                    <h4 class="alert-heading"><span
                            style="font-size: smaller;"><?= LangManager::translate("votes.dashboard.stats.day") ?> :</span> <?= number_format(count($day)) ?>
                        <span class=""
                              style="text-transform: lowercase;font-size: smaller;"><?= LangManager::translate("votes.votes") ?></span>
                    </h4>
                </div>
                <div class="alert alert-primary text-center">
                    <h4 class="alert-heading"><span
                            style="font-size: smaller;"><?= LangManager::translate("votes.dashboard.stats.week") ?> : </span><?= number_format(count($week)) ?>
                        <span class=""
                              style="text-transform: lowercase;font-size: smaller;"><?= LangManager::translate("votes.votes") ?></span>
                    </h4>
                </div>
                <div class="alert alert-primary text-center">
                    <h4 class="alert-heading"><span
                            style="font-size: smaller;"><?= LangManager::translate("votes.dashboard.stats.month") ?> :</span> <?= number_format(count($month)) ?>
                        <span class=""
                              style="text-transform: lowercase;font-size: smaller;"><?= LangManager::translate("votes.votes") ?></span>
                    </h4>
                </div>
                <div class="alert alert-primary">
                    <h4 class="alert-heading text-center"><span
                            style="font-size: smaller;"><?= LangManager::translate("votes.dashboard.stats.totals") ?></span>
                        : <?= number_format(count($all)) ?> <span class=""
                                                                  style="text-transform: lowercase;font-size: smaller;"><?= LangManager::translate("votes.votes") ?></span>
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-9">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.stats.3pastsMonths") ?></h4>
            </div>
            <div class="card-body">
                <div class="chartjs-size-monitor">
                    <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                    </div>
                    <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                    </div>
                </div>
                <canvas id="chartGlobal"
                        style="min-height: 340px; height: 340px; max-height: 340px; max-width: 100%; display: block; width: 765px;"
                        width="765" height="340" class="chartjs-render-monitor"></canvas>
            </div>
        </div>
    </div>
</section>

<section class="row">
    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.stats.sites_current") ?></h4>
            </div>
            <div class="card-body">
                <div class="chart">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="chartSiteTotals" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("votes.dashboard.stats.sites_totals") ?></h4>
            </div>
            <div class="card-body">
                <div class="chart">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="chartSiteMonth" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= LangManager::translate("votes.dashboard.stats.top_current") ?></h4>
            </div>
            <div class="card-body">
                <table id="table1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><?= LangManager::translate("users.users.pseudo") ?></th>
                        <th><?= LangManager::translate("votes.votes") ?></th>
                        <th><?= LangManager::translate("users.users.mail") ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $actualTop */
                    foreach ($actualTop as $player) : ?>
                        <tr>
                            <td><?= $player->getUser()->getPseudo() ?></td>
                            <td><?= $player->getVotes() ?></td>
                            <td><?= $player->getUser()->getMail() ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= LangManager::translate("users.users.pseudo") ?></th>
                        <th><?= LangManager::translate("votes.votes") ?></th>
                        <th><?= LangManager::translate("users.users.mail") ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<section class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= LangManager::translate("votes.dashboard.stats.top_totals") ?></h4>
            </div>
            <div class="card-body">
                <table id="table2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><?= LangManager::translate("users.users.pseudo") ?></th>
                        <th><?= LangManager::translate("votes.votes") ?></th>
                        <th><?= LangManager::translate("users.users.mail") ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $globalTop */
                    foreach ($globalTop as $player) : ?>
                        <tr>
                            <td><?= $player->getUser()->getPseudo() ?></td>
                            <td><?= $player->getVotes() ?></td>
                            <td><?= $player->getUser()->getMail() ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= LangManager::translate("users.users.pseudo") ?></th>
                        <th><?= LangManager::translate("votes.votes") ?></th>
                        <th><?= LangManager::translate("users.users.mail") ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= LangManager::translate("votes.dashboard.stats.top_pastMonth") ?></h4>
            </div>
            <div class="card-body">
                <table id="table3" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><?= LangManager::translate("users.users.pseudo") ?></th>
                        <th><?= LangManager::translate("votes.votes") ?></th>
                        <th><?= LangManager::translate("users.users.mail") ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $previousTop */
                    foreach ($previousTop as $player) : ?>
                        <tr>
                            <td><?= $player->getUser()->getPseudo() ?></td>
                            <td><?= $player->getVotes() ?></td>
                            <td><?= $player->getUser()->getMail() ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><?= LangManager::translate("users.users.pseudo") ?></th>
                        <th><?= LangManager::translate("votes.votes") ?></th>
                        <th><?= LangManager::translate("users.users.mail") ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    //Get months
    function getLast3Months() {

        const monthNames = <?= LangManager::translate("core.months") ?>

        const today = new Date();
        let toReturn = [];

        for (let i = 0; i < 3; i++) {
            toReturn.push(monthNames[(today.getMonth() - i)]);
        }
        return toReturn.reverse();
    }

    //Chart global
    const ctxGlobal = document.getElementById('chartGlobal').getContext('2d');
    const chartGlobal = new Chart(ctxGlobal, {
        type: 'line',
        data: {
            labels: getLast3Months(),
            datasets: [{
                label: "Votes du mois",
                data: <?= json_encode($previous3Months, JSON_THROW_ON_ERROR) ?>,
                backgroundColor: "#6B48FF",
                borderColor: "#6B48FF",

            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                },
            }
        }
    });
    //Chart Site month
    const ctxSiteMonth = document.getElementById('chartSiteMonth').getContext('2d');
    const chartSiteMonth = new Chart(ctxSiteMonth, {
        type: 'doughnut',
        data: {
            //website name
            labels: [
                <?php foreach ($listSites as $site):
                echo json_encode($site->getTitle(), JSON_THROW_ON_ERROR) . ",";
            endforeach;?>
            ],
            datasets: [{
                //Number of votes
                data: [
                    <?php foreach ($listSites as $site):
                    echo json_encode($stats->statsVotesSitesMonth($site->getTitle()), JSON_THROW_ON_ERROR) . ",";
                endforeach;?>
                ],
                //Color (random)
                backgroundColor: [
                    <?php for ($i = 0,$iMax = count($listSites); $i < $iMax ; $i++): ?>
                    <?= "random_rgb()," ?>
                    <?php endfor; ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
        }
    });


    //Chart Site totals
    const ctxSiteTotals = document.getElementById('chartSiteTotals').getContext('2d');
    const chartSiteTotals = new Chart(ctxSiteTotals, {
        type: 'doughnut',
        data: {
            //website name
            labels: [
                <?php foreach ($listSites as $site):
                echo json_encode($site->getTitle(), JSON_THROW_ON_ERROR) . ",";
            endforeach;?>
            ],
            datasets: [{
                //Number of votes
                data: [
                    <?php foreach ($listSites as $site):
                    echo json_encode($stats->statsVotesSitesTotaux($site->getTitle()), JSON_THROW_ON_ERROR) . ",";
                endforeach;?>
                ],
                //Color (random)
                backgroundColor: [
                    <?php for ($i = 0,$iMax = count($listSites); $i < $iMax ; $i++): ?>
                    <?= "random_rgb()," ?>
                    <?php endfor; ?>
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
        }
    });

</script>