<?php

namespace elitedivision\amos\core\forms\editors\assets;

use yii\web\AssetBundle;

class EditorsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/elite-division/amos-core/forms/editors/assets/web';

    public $css = [
    ];
    public $js = [
        'js/cartewidget.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}