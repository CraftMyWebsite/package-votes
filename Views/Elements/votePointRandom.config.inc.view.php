<?php

/* @var string $varName */

?>
<div class="mt-3 mb-4 row">
        <div class="col-12 col-lg-6">
            <label for="<?=$varName?>_min">Mini :</label>
            <input value="" placeholder="Montant" type="number" id="<?=$varName?>_min" name="<?=$varName?>_min" class="form-control" required="true">
        </div>
        <div class="col-12 col-lg-6">
            <label for="<?=$varName?>_max">Maxi :</label>
                <input value="" placeholder="Montant" type="number" id="<?=$varName?>_max" name="<?=$varName?>_max" class="form-control" required="true">
        </div>
</div>
