<?php

$title = "Voter";
$description = "Votez pour votre serveur préféré !";

/* @var \CMW\Entity\Votes\VotesSitesEntity[] $sites */
?>


<div class="container">
    <div class="row"
    <div class="col">
        <?php foreach ($sites as $site): ?>
            <div class="package">
                <div class="package__info">
                    <h3><?= $site->getTitle() ?></h3>
                    <div class="package__tags">
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
