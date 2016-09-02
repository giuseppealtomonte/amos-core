<?php

namespace elitedivision\amos\core\views;


use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class DataProviderView
 * @package backend\components\views
 * decorator for every view in amos
 */
class DataProviderView extends Widget
{
    public $view;
    public $currentView;

    public $viewListClass = 'elitedivision\amos\core\views\ListView';
    public $viewGridClass = 'elitedivision\amos\core\views\AmosGridView';
    public $viewIconClass = 'elitedivision\amos\core\views\IconView';
    public $viewMapClass = 'elitedivision\amos\core\views\MapView';
    public $viewCalendarClass = 'elitedivision\amos\core\views\CalendarView';

    public $dataProvider;

    public $gridView;
    public $listView;
    public $iconView;
    public $mapView;
    public $calendarView;

    public function run()
    {

        $viewClass = $this->{'view' . ucfirst(strtolower($this->currentView['name'])) . 'Class'};
        $viewParams = $this->{strtolower($this->currentView['name']) . 'View'};
        $view =
            ArrayHelper::merge(
                [
                    'class' => $viewClass,
                    'dataProvider' => $this->getDataProvider(),                    
                ], $viewParams);

        $this->view = \Yii::createObject($view);

        return $this->view->run();
    }

    /**
     * @return mixed
     */
    public function getCurrentView()
    {
        return $this->currentView;
    }

    /**
     * @param mixed $currentView
     */
    public function setCurrentView($currentView)
    {
        $this->currentView = $currentView;
    }

    /**
     * @return string
     */
    public function getViewListClass()
    {
        return $this->viewListClass;
    }

    /**
     * @param string $viewListClass
     */
    public function setViewListClass($viewListClass)
    {
        $this->viewListClass = $viewListClass;
    }

    /**
     * @return string
     */
    public function getViewGridClass()
    {
        return $this->viewGridClass;
    }

    /**
     * @param string $viewGridClass
     */
    public function setViewGridClass($viewGridClass)
    {
        $this->viewGridClass = $viewGridClass;
    }

    /**
     * @return string
     */
    public function getViewIconClass()
    {
        return $this->viewIconClass;
    }

    /**
     * @param string $viewIconClass
     */
    public function setViewIconClass($viewIconClass)
    {
        $this->viewIconClass = $viewIconClass;
    }

    /**
     * @return mixed
     */
    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * @param mixed $dataProvider
     */
    public function setDataProvider($dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return mixed
     */
    public function getGridView()
    {
        return $this->gridView;
    }

    /**
     * @param mixed $gridView
     */
    public function setGridView($gridView)
    {
        $this->gridView = $gridView;
    }

    /**
     * @return mixed
     */
    public function getListView()
    {
        return $this->listView;
    }

    /**
     * @param mixed $listView
     */
    public function setListView($listView)
    {
        $this->listView = $listView;
    }

    /**
     * @return mixed
     */
    public function getIconView()
    {
        return $this->iconView;
    }

    /**
     * @param mixed $iconView
     */
    public function setIconView($iconView)
    {
        $this->iconView = $iconView;
    }


}