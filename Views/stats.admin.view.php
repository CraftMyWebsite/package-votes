<?php

use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Manager\Lang\LangManager;

$title = LangManager::translate('votes.dashboard.title.stats');
$description = LangManager::translate('votes.dashboard.desc');

/* @var $stats */
/* @var $all */
/* @var $month */
/* @var $week */
/* @var $day */
/* @var VotesSitesEntity[] $listSites */
/* @var $previous3Months [] */
?>

<h3><i class="fas fa-chart-area"></i> <?= LangManager::translate('votes.dashboard.title.stats') ?></h3>

<div class="grid-3">
    <div class="card">
        <h6><?= LangManager::translate('votes.dashboard.stats.3pastsMonths') ?></h6>
        <div id="chartGlobal"></div>
    </div>
    <div class="card">
        <h6><?= LangManager::translate('votes.dashboard.stats.sites_current') ?></h6>
        <div id="chartSiteMonth"></div>
    </div>
    <div class="card">
        <h6><?= LangManager::translate('votes.dashboard.stats.sites_totals') ?></h6>
        <div id="chartSiteTotals"></div>
    </div>
</div>

<div class="grid-4 mt-4">
    <div class="alert alert-primary text-center">
        <h4 class="alert-heading"><span
                style="font-size: smaller;"><?= LangManager::translate('votes.dashboard.stats.day') ?> :</span> <?= number_format(count($day)) ?>
            <span class=""
                  style="text-transform: lowercase;font-size: smaller;"><?= LangManager::translate('votes.votes') ?></span>
        </h4>
    </div>
    <div class="alert alert-primary text-center">
        <h4 class="alert-heading"><span
                style="font-size: smaller;"><?= LangManager::translate('votes.dashboard.stats.week') ?> : </span><?= number_format(count($week)) ?>
            <span class=""
                  style="text-transform: lowercase;font-size: smaller;"><?= LangManager::translate('votes.votes') ?></span>
        </h4>
    </div>
    <div class="alert alert-primary text-center">
        <h4 class="alert-heading"><span
                style="font-size: smaller;"><?= LangManager::translate('votes.dashboard.stats.month') ?> :</span> <?= number_format(count($month)) ?>
            <span class=""
                  style="text-transform: lowercase;font-size: smaller;"><?= LangManager::translate('votes.votes') ?></span>
        </h4>
    </div>
    <div class="alert alert-primary">
        <h4 class="alert-heading text-center"><span
                style="font-size: smaller;"><?= LangManager::translate('votes.dashboard.stats.totals') ?></span>
            : <?= number_format(count($all)) ?> <span class=""
                                                      style="text-transform: lowercase;font-size: smaller;"><?= LangManager::translate('votes.votes') ?></span>
        </h4>
    </div>
</div>

<div class="grid-3 mt-4">
    <div class="card">
            <h6 class="card-title"><?= LangManager::translate('votes.dashboard.stats.top_current') ?></h6>
        <div class="table-container table-container-striped">
            <table id="table1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><?= LangManager::translate('users.users.pseudo') ?></th>
                    <th><?= LangManager::translate('votes.votes') ?></th>
                    <th><?= LangManager::translate('users.users.mail') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    /** @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $actualTop */
                    foreach ($actualTop as $player):
                ?>
                    <tr>
                        <td><?= $player->getUser()->getPseudo() ?></td>
                        <td><?= $player->getVotes() ?></td>
                        <td><?= $player->getUser()->getMail() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th><?= LangManager::translate('users.users.pseudo') ?></th>
                    <th><?= LangManager::translate('votes.votes') ?></th>
                    <th><?= LangManager::translate('users.users.mail') ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
            <h6 class="card-title"><?= LangManager::translate('votes.dashboard.stats.top_pastMonth') ?></h6>
        <div class="table-container table-container-striped">
            <table id="table3" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><?= LangManager::translate('users.users.pseudo') ?></th>
                    <th><?= LangManager::translate('votes.votes') ?></th>
                    <th><?= LangManager::translate('users.users.mail') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    /** @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $previousTop */
                    foreach ($previousTop as $player):
                ?>
                    <tr>
                        <td><?= $player->getUser()->getPseudo() ?></td>
                        <td><?= $player->getVotes() ?></td>
                        <td><?= $player->getUser()->getMail() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th><?= LangManager::translate('users.users.pseudo') ?></th>
                    <th><?= LangManager::translate('votes.votes') ?></th>
                    <th><?= LangManager::translate('users.users.mail') ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
        <h6 class="card-title"><?= LangManager::translate('votes.dashboard.stats.top_totals') ?></h6>
        <div class="table-container table-container-striped">
            <table id="table2" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><?= LangManager::translate('users.users.pseudo') ?></th>
                    <th><?= LangManager::translate('votes.votes') ?></th>
                    <th><?= LangManager::translate('users.users.mail') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    /** @var \CMW\Entity\Votes\VotesPlayerStatsEntity[] $globalTop */
                    foreach ($globalTop as $player):
                ?>
                    <tr>
                        <td><?= $player->getUser()->getPseudo() ?></td>
                        <td><?= $player->getVotes() ?></td>
                        <td><?= $player->getUser()->getMail() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th><?= LangManager::translate('users.users.pseudo') ?></th>
                    <th><?= LangManager::translate('votes.votes') ?></th>
                    <th><?= LangManager::translate('users.users.mail') ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    function getLast3Months() {

        const monthNames = <?= LangManager::translate('core.months.list') ?>

        const today = new Date();
        let toReturn = [];

        for (let i = 0; i < 3; i++) {
            toReturn.push(monthNames[(today.getMonth() - i)]);
        }
        return toReturn.reverse();
    }
    var areaOptions = {
        chart: {
            type: 'area'
        },
        series: [{
            name: 'Votes',
            data: <?= json_encode($previous3Months, JSON_THROW_ON_ERROR) ?>
        }],
        xaxis: {
            categories: getLast3Months()
        }
    };

    var areaChart = new ApexCharts(document.querySelector("#chartGlobal"), areaOptions);
    areaChart.render();

    var polarAreaOptions1 = {
        chart: {
            type: 'polarArea'
        },
        series: [<?php foreach ($listSites as $site):
    echo json_encode($stats->statsVotesSitesMonth($site->getTitle()), JSON_THROW_ON_ERROR) . ',';
endforeach; ?>],
        labels: [<?php foreach ($listSites as $site):
    echo json_encode($site->getTitle(), JSON_THROW_ON_ERROR) . ',';
endforeach; ?>]
    };
    var polarAreaChart1 = new ApexCharts(document.querySelector("#chartSiteMonth"), polarAreaOptions1);
    polarAreaChart1.render();

    var polarAreaOptions2 = {
        chart: {
            type: 'polarArea'
        },
        series: [<?php foreach ($listSites as $site):
    echo json_encode($stats->statsVotesSitesTotaux($site->getTitle()), JSON_THROW_ON_ERROR) . ',';
endforeach; ?>],
        labels: [<?php foreach ($listSites as $site):
    echo json_encode($site->getTitle(), JSON_THROW_ON_ERROR) . ',';
endforeach; ?>]
    };
    var polarAreaChart2 = new ApexCharts(document.querySelector("#chartSiteTotals"), polarAreaOptions1);
    polarAreaChart2.render();
</script>