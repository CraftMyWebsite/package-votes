<?php

use CMW\Manager\Lang\LangManager;
use CMW\Model\Votes\VotesStatsModel;

?>
<section>
    <h3><i class="fas fa-vote-yea"></i> <?= LangManager::translate('votes.votes') ?></h3>
    <div class="grid-4">
        <div class="card text-center">
            <div class="center-flex items-center gap-6 py-4">
                <i class="w-24 fa-solid fa-calendar-day text-3xl rounded-lg p-3 text-white" style="background-color: #5DDAB4"></i>
                <div class="w-1/2">
                    <p class="text-muted font-semibold"><?= LangManager::translate('votes.votes')
    . ' ' . mb_strtolower(LangManager::translate('votes.dashboard.stats.day')) ?></p>
                    <h6 class="font-extrabold mb-0"><?= number_format(count(VotesStatsModel::getInstance()->statsVotes('day'))) ?></h6>
                </div>
            </div>
        </div>
        <div class="card text-center">
            <div class="center-flex items-center gap-6 py-4">
                <i class="w-24 fa-solid fa-calendar-days text-3xl rounded-lg p-3 text-white" style="background-color: #5d89da"></i>
                <div class="w-1/2">
                    <p class="text-muted font-semibold"><?= LangManager::translate('votes.votes')
    . ' ' . mb_strtolower(LangManager::translate('votes.dashboard.stats.month')) ?></p>
                    <h6 class="font-extrabold mb-0"><?= number_format(count(VotesStatsModel::getInstance()->statsVotes('month'))) ?></h6>
                </div>
            </div>
        </div>
    </div>
</section>