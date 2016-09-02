<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace elitedivision\amos\core\views\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AmosCoreIeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/elite-division/amos-core/views/assets/web';

    public $css = [
        'css/style_table_responsive-ie.css',
    ];
    /*public $cssOptions = [
        'condition' => 'IE',
    ];*/
    public $js = [       
    ];
   /* public $depends = [
        'elitedivision\amos\core\views\assets\AmosCoreAsset',
    ];*/
}
