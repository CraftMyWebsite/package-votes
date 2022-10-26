<?php

use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Manager\Lang\LangManager;
use CMW\Model\Votes\VotesStatsModel;

$title = LangManager::translate("votes.dashboard.title.stats");
$description = LangManager::translate("votes.dashboard.desc");


/* @var $stats */
/* @var $all */
/* @var $month */
/* @var $week */
/* @var $day */
/* @var VotesSitesEntity[] $listSites */
/* @var $numberOfSites */

$scripts = '
    <script>
    $(function () {
        $("table[id^='."datatable-".']").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            language: {
            processing:     "' . LangManager::translate("core.datatables.list.processing") . '",
                search:         "' . LangManager::translate("core.datatables.list.search") . '",
                lengthMenu:    "' . LangManager::translate("core.datatables.list.lenghtmenu") . '",
                info:           "' . LangManager::translate("core.datatables.list.info") . '",
                infoEmpty:      "' . LangManager::translate("core.datatables.list.info_empty") . '",
                infoFiltered:   "' . LangManager::translate("core.datatables.list.info_filtered") . '",
                infoPostFix:    "' . LangManager::translate("core.datatables.list.info_postfix") . '",
                loadingRecords: "' . LangManager::translate("core.datatables.list.loadingrecords") . '",
                zeroRecords:    "' . LangManager::translate("core.datatables.list.zerorecords") . '",
                emptyTable:     "' . LangManager::translate("core.datatables.list.emptytable") . '",
                paginate: {
                first:      "' . LangManager::translate("core.datatables.list.first") . '",
                    previous:   "' . LangManager::translate("core.datatables.list.previous") . '",
                    next:       "' . LangManager::translate("core.datatables.list.next") . '",
                    last:       "' . LangManager::translate("core.datatables.list.last") . '"
                },
                aria: {
                sortAscending:  "' . LangManager::translate("core.datatables.list.sort.ascending") . '",
                    sortDescending: "' . LangManager::translate("core.datatables.list.sort.descending") . '"
                }
            },
        });
    });
</script>'; ?>

<div class="container-fluid">
    <div class="row">

        <!-- All votes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= number_format(count($all)) ?></h3>

                    <p><?= LangManager::translate("votes.dashboard.stats.totals") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-vote-yea"></i>
                </div>
            </div>
        </div>

        <!-- Votes of the month -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= number_format(count($month)) ?></h3>

                    <p><?= LangManager::translate("votes.dashboard.stats.month") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-vote-yea"></i>
                </div>
            </div>
        </div>

        <!-- Votes of the week -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= number_format(count($week)) ?></h3>

                    <p><?= LangManager::translate("votes.dashboard.stats.week") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-vote-yea"></i>
                </div>
            </div>
        </div>

        <!-- Votes of the day -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= number_format(count($day)) ?></h3>

                    <p><?= LangManager::translate("votes.dashboard.stats.day") ?></p>
                </div>
                <div class="icon">
                    <i class="fas fa-vote-yea"></i>
                </div>
            </div>
        </div>

    </div>


    <div class="row">
        <!-- STATS "globaux" -->
        <div class="col">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Stats globaux -- tests</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
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
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 765px;"
                            width="765" height="250" class="chartjs-render-monitor"></canvas>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Votes par site (totaux)</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
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
                        <canvas id="chartSiteTotals"
                                style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%; display: block; width: 765px;"
                                width="765" height="250" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>

            </div>

        </div>
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Votes par site (mois en cours)</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
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
                        <canvas id="chartSiteMonth"
                                style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%; display: block; width: 765px;"
                                width="765" height="250" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Top voteurs mois en cours</h3>
        </div>
        <div class="card-body">
            <table id="datatable-1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var VotesStatsModel[] $actualTop */
                foreach ($actualTop as $player) : ?>
                    <tr>
                        <td><?= $player['pseudo'] ?></td>
                        <td><?= $player['votes'] ?></td>
                        <td><?= $player['email'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Top voteurs totaux</h3>
        </div>
        <div class="card-body">
            <table id="datatable-2" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var VotesStatsModel[] $globalTop */
                foreach ($globalTop as $player) : ?>
                    <tr>
                        <td><?= $player['pseudo'] ?></td>
                        <td><?= $player['votes'] ?></td>
                        <td><?= $player['email'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Classement du mois précédent</h3>
        </div>
        <div class="card-body">
            <table id="datatable-3" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var VotesStatsModel[] $previousTop */
                foreach ($previousTop as $player) : ?>
                    <tr>
                        <td><?= $player['pseudo'] ?></td>
                        <td><?= $player['votes'] ?></td>
                        <td><?= $player['email'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<pre>
        <strong>Statistiques prévus:</strong>

        - Stats 'globaux' -> totaux, mois, semaine, jour ✔
        - Stats chart du nombre de votes par sites (totaux, mois en cours) ✔
        - Stats chart du nombre de votes par -> Mois (line chart)
        - Liste des top voteurs -> Totaux, mois, semaine, jour

    </pre>


<!-- First chart test-->
<script>
    //Get months


    //Chart global
    const ctxGlobal = document.getElementById('chartGlobal').getContext('2d');
    const chartGlobal = new Chart(ctxGlobal, {
        type: 'line',
        data: {
            labels: [
                "Janvier",
                "Fevrier",
                "Mars",
            ],
            datasets: [{
                label: "Votes",
                data: [
                    "100",
                    "120",
                    "200",
                ],
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
                    <?php for ($i = 0; $i < $numberOfSites; $i++): ?>
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
                    <?php for ($i = 0; $i < $numberOfSites; $i++): ?>
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