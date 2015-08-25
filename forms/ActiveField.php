<?php

namespace elitedivision\amos\core\forms;


use elitedivision\amos\core\icons\AmosIcons;
use yii\bootstrap\ActiveField as YiiActiveField;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActiveField extends YiiActiveField
{

    public function init()
    {
        parent::init();

        $this->errorOptions = ArrayHelper::merge($this->errorOptions, [
            'tag' => 'span'
        ]);
        $this->hintOptions = ArrayHelper::merge($this->hintOptions, [
            'tag' => 'span'
        ]);

        $hint = $this->model->getAttributeHint($this->attribute);
        if ($hint) {
            $this->parts['{hint}'] = Html::tag('span', AmosIcons::show('help-alt'), [
                //'class' => 'text-right',
                'data-toggle' => "tooltip",
                'data-placement' => "top",
                'title' => html_entity_decode($hint)
            ]);
        }
        $error = $this->model->getErrors($this->attribute);
        if (count($error)) {
            $this->parts['{error}'] = Html::tag('span', AmosIcons::show('alert'), [
                //'class' => 'text-right',
                'data-toggle' => "tooltip",
                'data-placement' => "top",
                'title' => html_entity_decode(implode("\n", $error))
            ]);
        }
    }

}