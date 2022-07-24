<?php

$title = "Voter";
$description = "Votez pour votre serveur préféré !";

/* @var $sites */

?>
<pre>
    Première page de test, pour le moment il faut jquery pour fonctionner.
</pre>

    <div class="container">
        <div class="row"
        <div class="col">
            <?php foreach ($sites as $site): ?>

                <div class="card mr-3">
                    <div class="card-header">
                        <p><?= $site['title'] ?></p>
                    </div>
                    <div class="card-body">
                        <p>Temps de vote : <strong><?= $site['time'] ?></strong> minutes</p>
                    </div>
                    <div class="card-footer text-center">
                        <input type="hidden" id="idSite" value="<?= $site['id'] ?>" hidden>
                        <input type="hidden" id="urlSite" value="<?= $site['url'] ?>" hidden>
                        <input type="hidden" id="token" value="<?=  $_SESSION['votes']['token'] ?>" hidden>

                        <button type="button" rel="noopener noreferrer" class="btn btn-success"
                                name="btnVote" value="<?= $site['url'] ?>">Voter</button>
                    </div>
                </div>



            <?php endforeach; ?>
        </div>
    </div>
    </div>

    <script src="app/package/votes/views/ressources/js/public.js"></script>