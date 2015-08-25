<?php

function preparaUrl($stringa){
    $nuovaStringa = substr($stringa, 1, strlen($stringa)-2);
    return $nuovaStringa;
}
?>
<?=
'use elitedivision\amos\core\helpers\Html;
    
/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */

<div id="listViewListContainer">
    <div class="bk-listViewElement">        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2><?= $model ?></h2>            
            <p>####### personalizzare l&#39;html a piacimento #######</p>
        </div>
        <div class="bk-elementActions">
            <a href="'. preparaUrl($generator->viewPath) . '?id=<?= $model->id ?>"><button class="btn btn-success">Visualizza</button></a>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>'
?>