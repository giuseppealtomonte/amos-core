<?php

namespace elitedivision\amos\core\forms\editors\assets;

use yii\web\AssetBundle;

class EditorsAsset extends AssetBundle
{
    public $sourcePath = '@backend/components/forms/editors/assets/web';

    public $css = [
    ];
    public $js = [
        'js/cartewidget.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}