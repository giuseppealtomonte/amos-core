<?php

namespace elitedivision\amos\core\forms;


use elitedivision\amos\core\icons\AmosIcons;
use yii\bootstrap\ActiveForm as YiiActiveForm;
use yii\helpers\Html;

class ActiveForm extends YiiActiveForm
{

    public function init()
    {
        $this->fieldConfig = [
            'template' => "<div class=\"row\">
                <div class=\"col-xs-6\">{label}</div>
                <div class=\"col-xs-6\"> <span class=\"tooltip-field pull-right\"> {hint} </span> <span class=\"tooltip-error-field pull-right\"> {error} </span> </div>
            \n<div class=\"col-xs-12\">{input}</div>
            </div>",
        ];
        $this->fieldClass = 'backend\components\forms\ActiveField';

        echo Html::tag('span', AmosIcons::show('alert'), [
            'id' => 'errore-alert-common',
            'class' => 'errore-alert bk-noDisplay',
            'title' => \Yii::t('app', 'La tab contiene degli errori')
        ]);

        parent::init();
    }

}