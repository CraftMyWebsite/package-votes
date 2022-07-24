<?php
$title = VOTES_DASHBOARD_TITLE_STATS;
$description = VOTES_DASHBOARD_DESC;


/* @var $stats */
/* @var $all */
/* @var $month */
/* @var $week */
/* @var $day */
/* @var $listSites */
/* @var $numberOfSites */

$styles = '<link rel="stylesheet" href="' . getenv("PATH_SUBFOLDER") . 'admin/resources/vendors/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="' . getenv("PATH_SUBFOLDER") . 'admin/resources/vendors/datatables-responsive/css/responsive.bootstrap4.min.css">';


$scripts = '<script src="' . getenv("PATH_SUBFOLDER") . 'admin/resources/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="' . getenv("PATH_SUBFOLDER") . 'admin/resources/vendors/datatables/jquery.dataTables.min.js"></script>
<script src="' . getenv("PATH_SUBFOLDER") . 'admin/resources/vendors/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="' . getenv("PATH_SUBFOLDER") . 'admin/resources/vendors/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="' . getenv("PATH_SUBFOLDER") . 'admin/resources/vendors/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    $(function () {
        $("#users_table").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            language: {
                processing:     "' . CORE_DATATABLES_LIST_PROCESSING . '",
                search:         "' . CORE_DATATABLES_LIST_SEARCH . '",
                lengthMenu:     "' . CORE_DATATABLES_LIST_LENGTHMENU . '",
                info:           "' . CORE_DATATABLES_LIST_INFO . '",
                infoEmpty:      "' . CORE_DATATABLES_LIST_INFOEMPTY . '",
                infoFiltered:   "' . CORE_DATATABLES_LIST_INFOFILTERED . '",
                infoPostFix:    "' . CORE_DATATABLES_LIST_INFOPOSTFIX . '",
                loadingRecords: "' . CORE_DATATABLES_LIST_LOADINGRECORDS . '",
                zeroRecords:    "' . CORE_DATATABLES_LIST_ZERORECORDS . '",
                emptyTable:     "' . CORE_DATATABLES_LIST_EMPTYTABLE . '",
                paginate: {
                    first:      "' . CORE_DATATABLES_LIST_FIRST . '",
                    previous:   "' . CORE_DATATABLES_LIST_PREVIOUS . '",
                    next:       "' . CORE_DATATABLES_LIST_NEXT . '",
                    last:       "' . CORE_DATATABLES_LIST_LAST . '"
                },
                aria: {
                    sortAscending:  "' . CORE_DATATABLES_LIST_SORTASCENDING . '",
                    sortDescending: "' . CORE_DATATABLES_LIST_SORTDESCENDING . '"
                }
            },
        });
    });
</script>'; ?>
<!-- Chart.js (lib) -->
<script src="<?= getenv("PATH_SUBFOLDER") ?>admin/resources/vendors/chart.js/Chart.min.js"></script>

<!-- Main.js -->
<script rel="script" src="<?= getenv("PATH_SUBFOLDER") ?>app/package/votes/views/ressources/js/main.js"></script>

<div class="container-fluid">
    <div class="row">

        <!-- All votes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= number_format(count($all)); ?></h3>

                    <p><?= VOTES_DASHBOARD_STATS_TOTALS ?></p>
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
                    <h3><?= number_format(count($month)); ?></h3>

                    <p><?= VOTES_DASHBOARD_STATS_MONTHS ?></p>
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
                    <h3><?= number_format(count($week)); ?></h3>

                    <p><?= VOTES_DASHBOARD_STATS_WEEK ?></p>
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
                    <h3><?= number_format(count($day)); ?></h3>

                    <p><?= VOTES_DASHBOARD_STATS_DAY ?></p>
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
            <table id="users_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var statsModel[] $actualTop */
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
            <table id="users_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var statsModel[] $globalTop */
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
            <table id="users_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Votes</th>
                    <th>E-mail</th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var statsModel[] $previousTop */
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
    var ctxGlobal = document.getElementById('chartGlobal').getContext('2d');
    var chartGlobal = new Chart(ctxGlobal, {
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
    var ctxSiteTotals = document.getElementById('chartSiteTotals').getContext('2d');
    var chartSiteTotals = new Chart(ctxSiteTotals, {
        type: 'doughnut',
        data: {
            //website name
            labels: [
                <?php foreach ($listSites as $site):
                echo json_encode($site['title']) . ",";
            endforeach;?>
            ],
            datasets: [{
                //Number of votes
                data: [
                    <?php foreach ($listSites as $site):
                    echo json_encode($stats->statsVotesSitesTotaux($site['title'])) . ",";
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
    var ctxSiteMonth = document.getElementById('chartSiteMonth').getContext('2d');
    var chartSiteMonth = new Chart(ctxSiteMonth, {
        type: 'doughnut',
        data: {
            //website name
            labels: [
                <?php foreach ($listSites as $site):
                echo json_encode($site['title']) . ",";
            endforeach;?>
            ],
            datasets: [{
                //Number of votes
                data: [
                    <?php foreach ($listSites as $site):
                    echo json_encode($stats->statsVotesSitesMonth($site['title'])) . ",";
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