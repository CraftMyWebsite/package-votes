<?php

use CMW\Manager\Lang\LangManager;
use CMW\Model\Votes\VotesStatsModel;

?>
<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fas fa-vote-yea"></i>
        <span class="m-lg-auto">
            <?= LangManager::translate("votes.votes") ?>
        </span>
    </h3>
</div>
<div class="row">
    <div class="col-sm-6 col-xl-3 text-center">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-2 col-sm-4">
                        <div class="stats-icon purple mb-2">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                    </div>
                    <div class="col-10 col-sm-8">
                        <h6 class="text-muted font-semibold">
                            <?= LangManager::translate("votes.votes")
                            . " " . mb_strtolower(LangManager::translate("votes.dashboard.stats.day")) ?>
                        </h6>
                        <h6 class="font-extrabold mb-0">
                            <?= number_format(count(VotesStatsModel::getInstance()->statsVotes("day"))) ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 text-center">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-2 col-sm-4">
                        <div class="stats-icon purple mb-2">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                    </div>
                    <div class="col-10 col-sm-8">
                        <h6 class="text-muted font-semibold">
                            <?= LangManager::translate("votes.votes")
                            . " " . mb_strtolower(LangManager::translate("votes.dashboard.stats.month")) ?>
                        </h6>
                        <h6 class="font-extrabold mb-0">
                            <?= number_format(count(VotesStatsModel::getInstance()->statsVotes("month"))) ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>