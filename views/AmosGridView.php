<?php

namespace elitedivision\amos\core\views;

use yii\grid\GridView;

class AmosGridView extends GridView
{

    public $name = 'grid';

    public $panelTemplate = <<< HTML
            {items}
            {pager}
HTML;
    public $layout = "<div class=\"table-responsive\"> {items}\n{pager} </div>";

    public $tableOptions = ['class' => 'table table-striped'];

}