<?php

namespace elitedivision\amos\core\views\common;


use elitedivision\amos\core\icons\AmosIcons;
use elitedivision\amos\core\utilities\CurrentUser;
use Yii;
use yii\base\Object;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Buttons extends Object
{

    public $urlCreator;
    public $controller;

    public $buttons;
    public $template;

    public $_isDropdown;

    public $viewOptions;
    public $updateOptions;
    public $deleteOptions;


    public function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model) {
                if (!$this->can($model, 'read')) {
                    return '';
                }
                $options = $this->viewOptions;
                $title = Yii::t('app', 'Leggi');
                $icon = AmosIcons::show('file');
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model) {
                if (!$this->can($model, 'update')) {
                    return '';
                }
                $options = $this->updateOptions;
                $title = Yii::t('app', 'Modifica');
                $icon = AmosIcons::show('pencil');

                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
                if (!$this->can($model, 'delete')) {
                    return '';
                }
                $options = $this->deleteOptions;

                $title = Yii::t('app', 'Cancella');
                $icon = AmosIcons::show('trash');

                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(
                    [
                        'title' => $title,
                        'data-confirm' => Yii::t('app', 'Sei sicuro di voler cancellare questo elemento?'),
                        'data-method' => 'post',
                        'data-pjax' => '0'
                    ],
                    $options
                );
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
    }

    public function get_real_class($obj)
    {
        $classname = get_class($obj);

        if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }

        return $classname;
    }

    protected function can($model, $action)
    {
        $modelClassName =  $this->get_real_class($model);
        $permissionName = strtoupper($modelClassName . '_' . $action);

        return CurrentUser::getUser()->can($permissionName , ['model' => $model]);
    }

    /**
     * @inheritdoc
     */
    public function renderButtonsContent($model, $key, $index)
    {
        $content = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);

                return call_user_func($this->buttons[$name], $url, $model, $key);
            } else {
                return '';
            }
        }, $this->template);

        return Html::tag('div', $content, ['class' => 'buttons-container']);

    }


    public function createUrl($action, $model, $key, $index)
    {
        if ($this->urlCreator instanceof \Closure) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index);
        } else {
            $params = is_array($key) ? $key : ['id' => (string)$key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return Url::toRoute($params);
        }
    }


}