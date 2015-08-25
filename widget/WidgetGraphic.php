<?php

namespace elitedivision\amos\core\widget;

class WidgetGraphic extends WidgetAbstract
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if (\Yii::$app->getUser()->can($this->getWidgetPermission())) {
            return $this->getHtml();
        } else {
            return '';
        }
    }

    public function getHtml()
    {
        return "############GRAPHIC!############";
    }


}