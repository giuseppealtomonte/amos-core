<?php

namespace elitedivision\amos\core\views\common;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

class BaseListView extends ListView
{

    public $buttonObj;

    public $template = '{view} {update} {delete}';

    public $containerOptions = [
    ];

    public $itemsContainerTag = 'div';

    public $itemsContainerOptions = [
        'class' => "",
        "role" => "listbox",
        "data-role" => "list-view"
    ];

    public $buttons;
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


    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    protected function initDefaultButtons()
    {

        $buttonOptions =
            [
                'class' => $this->buttonClass,
                'template' => $this->template,
                '_isDropdown' => $this->_isDropdown,
                'viewOptions' => $this->viewOptions,
                'updateOptions' => $this->updateOptions,
                'deleteOptions' => $this->deleteOptions,
            ];

        $this->buttonObj = Yii::createObject($buttonOptions);
        $this->buttonObj->initDefaultButtons();
        $this->buttons = $this->buttonObj->buttons;
    }

    /**
     * Renders all data models.
     * @return string the rendering result
     */
    public function renderItems()
    {
        $models = $this->dataProvider->getModels();
        $keys = $this->dataProvider->getKeys();
        $content = [];
        foreach (array_values($models) as $index => $model) {
            $content[] = $this->renderItem($model, $keys[$index], $index);
        }
        $itemsHtml = Html::tag($this->itemsContainerTag, implode("\n", $content), $this->itemsContainerOptions);
        return Html::tag('div', $itemsHtml, $this->containerOptions);
    }

    /**
     * Renders a single data model.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key value associated with the data model
     * @param integer $index the zero-based index of the data model in the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderItem($model, $key, $index)
    {
        if ($this->itemView === null) {
            $content = $key;
        } elseif (is_string($this->itemView)) {
            $content = $this->getView()->render($this->itemView, array_merge([
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'widget' => $this,
                'buttons' => $this->buttonObj->renderButtonsContent($model, $key, $index)
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemView, $model, $key, $index, $this);
        }
        $options = $this->itemOptions;
        //$tag = ArrayHelper::remove($options, 'tag', 'div');
        return Html::tag('div', $content, $options);
    }

    public function run()
    {
        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $content = preg_replace_callback("/{\\w+}/", function ($matches) {
                $content = $this->renderSection($matches[0]);

                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        } else {
            $content = $this->renderEmpty();
        }

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::tag($tag, $content, $options);
    }
}