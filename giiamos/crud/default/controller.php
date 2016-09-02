<?php

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/**
 * This is the template for generating a CRUD controller class file.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/** @var ActiveRecordInterface $class */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) . '\base' ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
    use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
    use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use elitedivision\amos\core\icons\AmosIcons;
use elitedivision\amos\core\helpers\T;
use yii\helpers\Url;

/**
* <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
*/
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
public function init() {
        $this->setModelObj(new <?= $modelClass ?>());
        $this->setModelSearch(new <?= $modelClass ?>Search());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => T::tApp('{iconaTabella} Tabella', [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
                'name' => 'list',
                'label' => T::tApp('{iconaLista} Lista', [
                    'iconaLista' => AmosIcons::show('view-list')
                ]),
                'url' => '?currentView=list'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => T::tApp('{iconaElenco} Icone', [
                    'iconaElenco' => AmosIcons::show('view-grid')
                ]),
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => T::tApp('{iconaMappa} Mappa', [
                    'iconaMappa' => AmosIcons::show('map-alt')
                ]),
                'url' => '?currentView=map'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => T::tApp('{iconaCalendario} Calendario', [
                    'iconaCalendario' => AmosIcons::show('calendar')
                ]),
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
    }

/**
* Lists all <?= $modelClass ?> models.
* @return mixed
*/        
public function actionIndex()
{    
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
}

/**
* Displays a single <?= $modelClass ?> model.
* <?= implode("\n     * ", $actionParamComments) . "\n" ?>
* @return mixed
*/
public function actionView(<?= $actionParams ?>)
{
$model = $this->findModel(<?= $actionParams ?>);

if ($model->load(Yii::$app->request->post()) && $model->save()) {
return $this->redirect(['view', 'id' => $model-><?= $generator->getTableSchema()->primaryKey[0] ?>]);
} else {
return $this->render('view', ['model' => $model]);
}
}

/**
* Creates a new <?= $modelClass ?> model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
public function actionCreate()
{
$this->layout = "@backend/views/layouts/form";
$model = new <?= $modelClass ?>;

if ($model->load(Yii::$app->request->post()) && $model->save()) {
return $this->redirect(['view', <?= $urlParams ?>]);
} else {
return $this->render('create', [
'model' => $model,
]);
}
}

/**
* Updates an existing <?= $modelClass ?> model.
* If update is successful, the browser will be redirected to the 'view' page.
* <?= implode("\n     * ", $actionParamComments) . "\n" ?>
* @return mixed
*/
public function actionUpdate(<?= $actionParams ?>)
{
$this->layout = "@backend/views/layouts/form";
$model = $this->findModel(<?= $actionParams ?>);

if ($model->load(Yii::$app->request->post()) && $model->save()) {
return $this->redirect(['view', <?= $urlParams ?>]);
} else {
return $this->render('update', [
'model' => $model,
]);
}
}

/**
* Deletes an existing <?= $modelClass ?> model.
* If deletion is successful, the browser will be redirected to the 'index' page.
* <?= implode("\n     * ", $actionParamComments) . "\n" ?>
* @return mixed
*/
public function actionDelete(<?= $actionParams ?>)
{
$this->findModel(<?= $actionParams ?>)->delete();

return $this->redirect(['index']);
}

/**
* Finds the <?= $modelClass ?> model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* <?= implode("\n     * ", $actionParamComments) . "\n" ?>
* @return <?= $modelClass ?> the loaded model
* @throws NotFoundHttpException if the model cannot be found
*/
protected function findModel(<?= $actionParams ?>)
{
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
return $model;
} else {
throw new NotFoundHttpException('The requested page does not exist.');
}
}
}
