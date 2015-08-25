<?php

namespace elitedivision\amos\core\views;


use yii\base\InvalidConfigException;
use yii\bootstrap\BootstrapPluginAsset;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;

class ChangeView extends Dropdown
{
    public $views;

    public $encodeLabels = false;

    public $dropdownContainerOptions = [
        'class' => 'btn-group pull-right'

    ];

    public $dropdown;
    public $dropdownLabel;
    public $dropdownTag = 'button';
    public $dropdownOptions = [
        "class" => "btn bk-btnChangeView dropdown-toggle",
        "type" => "button", "id" => "bk-btnChangeView",
        "data-toggle" => "dropdown", "aria-expanded" => "true"
    ];

    public $options = [
        "class" => "dropdown-menu dropdown-menu-change-view dropdown-menu-right", "aria-labelledby" => "bk-btnChangeView", "role" => "menu"
    ];

    public function init()
    {
        if (!isset($this->views)) {
            throw new InvalidConfigException("'views' option is required.");
        }
        foreach ($this->views as $view) {
            $this->items[] = $view;
        }
    }

    public function run()
    {
        BootstrapPluginAsset::register($this->getView());
        $this->registerClientEvents();

        $buttonDropdown = $this->renderDropdown();

        $items = $this->renderItems($this->items, $this->options);
        $content = $buttonDropdown . $items;

        return Html::tag('div', $content, $this->dropdownContainerOptions);
    }

    public function renderDropdown()
    {
        return Html::tag($this->dropdownTag, $this->dropdownLabel ? : $this->dropdown['label'], $this->dropdownOptions);
    }

}