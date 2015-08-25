<?php

namespace elitedivision\amos\core\controllers;

use elitedivision\amos\core\helpers\T;
use elitedivision\amos\core\icons\AmosIcons;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller as BaseController;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

abstract class CrudController extends BaseController
{
    public $layout = '@backend/views/layouts/main';
    public $modelObj;
    public $modelClass;
    public $modelClassName;
    public $modelName;

    public $dataProvider;
    public $modelSearch;

    public $currentView;
    public $availableViews;

    public function behaviors()
    {        
        $rules = [
            [
                'allow' => true,
                'roles' => ['@'],
            ],

        ];

        return \yii\helpers\ArrayHelper::merge(parent::behaviors(),
        
        
        [
            /*'as access' => [
                'class' => 'mdm\admin\components\AccessControl',
                'allowActions' => [
                    'site/login',
                    'site/error',
                ],
                /*
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->getUser()->isGuest) {
                        Yii::$app->getSession()->addFlash('warning', Yii::t('app', 'La sessione è scaduta, effettua il login'));
                        Yii::$app->getUser()->loginRequired();
                    }
                    throw new ForbiddenHttpException(Yii::t('app', 'Non sei autorizzato a visualizzare questa pagina'));
                }
                */
            //],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => $rules,
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->getUser()->isGuest) {
                        Yii::$app->getSession()->addFlash('warning', Yii::t('app', 'La sessione è scaduta, effettua il login'));
                        Yii::$app->getUser()->loginRequired();
                    }
                    throw new ForbiddenHttpException(Yii::t('app', 'Non sei autorizzato a visualizzare questa pagina'));
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ]);
    }

    public function init()
    {
        parent::init();

        if (!isset($this->modelObj)) {
            throw new InvalidConfigException("{modelObj} must be set in your init function");
        }
        if (!isset($this->modelSearch)) {
            throw new InvalidConfigException("{modelSearch} must be set in your init function");
        }
        if (!isset($this->availableViews)) {
            throw new InvalidConfigException("{availableViews}: gridView,listView,mapView,calendarView.. must be set");
        }

        $this->initModelName();

        $this->initCurrentView();

        $this->initAvailableViews();

    }

    public function initAvailableViews()
    {
        if (!$this->getAvailableViews()) {
            $this->setAvailableViews([
                'grid' => [
                    'name'=>'grid',
                    'label' => T::tApp('{iconaTabella} Tabella', [
                        'iconaTabella' => AmosIcons::show('view-list-alt')
                    ]),
                    'url' => '?currentView=grid'
                ],
            ]);
        }
    }

    public function initCurrentView()
    {
        $currentView = $this->getDefaultCurrentView($this->getModelClassName());
        $this->setCurrentView($currentView);

        if ($currentViewName = Yii::$app->request->getQueryParam('currentView')) {
            $this->setCurrentView($this->getAvailableView($currentViewName));
        }
    }

    public function initModelName()
    {
        $refClass = new \ReflectionClass($this->getModelObj());
        $this->setModelClassName($refClass->getName());
        $this->setModelName($refClass->getShortName());
    }

    public function getAvailableView($name)
    {
        return $this->getAvailableViews()[$name];
    }

    /**
     * @return mixed
     */
    public function getAvailableViews()
    {
        return $this->availableViews;
    }

    /**
     * @param mixed $availableViews
     */
    public function setAvailableViews($availableViews)
    {
        $this->availableViews = $availableViews;
    }

    /**
     * @return mixed
     */
    public function getModelObj()
    {
        return $this->modelObj;
    }

    /**
     * @param mixed $modelObj
     */
    public function setModelObj($modelObj)
    {
        $this->modelObj = $modelObj;
    }


    /**
     * @return mixed
     */
    public function getModelClassName()
    {
        return $this->modelClassName;
    }

    /**
     * @param mixed $modelClassName
     */
    public function setModelClassName($modelClassName)
    {
        $this->modelClassName = $modelClassName;
    }

    /**
     * @return mixed
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @param mixed $modelName
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }

    public function can($strPermission)
    {
        $strName = strtoupper($this->getModelName() . '_' . $strPermission);
        return Yii::$app->getUser()->can($strName);
    }

    /**
     * @return Model
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    /**
     * @param Model $modelClass
     */
    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
    }


    /**
     * @return string
     */
    public function getCurrentView()
    {
        return $this->currentView;
    }

    /**
     * @param string $currentView
     */
    public function setCurrentView($currentView)
    {
        $this->currentView = $currentView;
    }

    protected function getDefaultCurrentView($modelClass)
    {
        $this->initAvailableViews();

        $views = array_keys($this->getAvailableViews());


        return $this->getAvailableView($views[0]);
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
    public function setDataProvider(ActiveDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return mixed
     */
    public function getModelSearch()
    {
        return $this->modelSearch;
    }

    /**
     * @param mixed $modelSearch
     */
    public function setModelSearch($modelSearch)
    {
        $this->modelSearch = $modelSearch;
    }

    /**
     * Finds the ModelClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModelClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIndex()
    {
        $this->layout = '@backend/views/layouts/list';
        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews()
        ]);
    }

}