<?php

namespace elitedivision\amos\core\views\grid;

use elitedivision\amos\core\icons\AmosIcons;
use elitedivision\amos\core\views\common\Buttons;
use Yii;
use yii\grid\ActionColumn as YiiActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

//\kartik\grid\ActionColumn

class ActionColumn extends YiiActionColumn
{

    public $buttonClass = 'elitedivision\amos\core\views\common\Buttons';
    public $viewOptions = [
        'class' => 'btn bk-btnMore'
    ];
    public $updateOptions = [
        'class' => 'btn bk-btnEdit'
    ];
    public $deleteOptions = [
        'class' => 'btn bk-btnDelete'
    ];

    public $_isDropdown = false;

    protected function renderDataCellContent($model, $key, $index)
    {
        $renderDataCellContent = parent:: renderDataCellContent($model, $key, $index);
        return Html::tag('div', $renderDataCellContent, ['class' => 'bk-elementActions']) . Html::tag('div', '', ['class' => 'clearfix']);
    }


    protected function initDefaultButtons()
    {

        $buttonOptions =
            [
                'class'=> $this->buttonClass,
                'template' => $this->template,
                '_isDropdown' => $this->_isDropdown,
                'viewOptions'=> $this->viewOptions,
                'updateOptions'=> $this->updateOptions,
                'deleteOptions'=> $this->deleteOptions,
                'buttons'=>$this->buttons,
            ];

        $Button = Yii::createObject($buttonOptions);
        $Button->initDefaultButtons();
        $this->buttons = $Button->buttons;
    }

}