<?php

namespace elitedivision\amos\core\icons;

use kartik\icons\Icon;
use Yii;
use yii\helpers\Html;


class AmosIcons extends Icon
{

    /**
     * Icon framework constants
     */
    const AM = 'am';
    const TI = 'ti';


    /**
     * Icon framework configurations
     */
    public static $_custom_frameworks = [
        self::AM => ['prefix' => 'am am-', 'class' => '\\backend\\assets\\AmosAsset'],
        self::TI => ['prefix' => 'ti ti-', 'class' => '\\backend\\assets\\ThemifyAsset'],
    ];

    public static function show($name, $options = [], $framework = null, $space = true, $tag = 'span')
    {
        $key = self::getFramework($framework);
        if (in_array($key, array_keys(self::$_custom_frameworks))) {
            $class = self::$_custom_frameworks[$key]['prefix'] . $name;
            Html::addCssClass($options, $class);
            return Html::tag($tag, '', $options) . ($space ? ' ' : '');
        } else {
            return parent::show($name, $options, $framework, $space, $tag);
        }

    }


    protected static function getFramework($framework = null, $method = 'show')
    {


        if (strlen($framework) == 0 && !empty(Yii::$app->params['icon-framework'])) {
            if (in_array(Yii::$app->params['icon-framework'], array_keys(self::$_custom_frameworks))) {
                return Yii::$app->params['icon-framework'];
            }
        } else {
            if (!in_array($framework, array_keys(self::$_custom_frameworks))) {
                return parent::getFramework($framework, $method);
            } else {
                return $framework;
            }
        }

        return parent::getFramework($framework, $method);
    }

    public static function map($view, $framework = null)
    {
        $key = self::getFramework($framework, 'map');

        if (in_array($key, array_keys(self::$_custom_frameworks))) {

            $class = self::$_custom_frameworks[$key]['class'];
            if (substr($class, 0, 1) != '\\') {
                $class = self::NS . $class;
            }

            $class::register($view);

        } else {
            parent::map($view, $framework);
        }
    }


}