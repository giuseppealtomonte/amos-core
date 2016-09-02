<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */
/** @var \yii\db\ActiveRecord $model */
$model = new $generator->modelClass;
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

$itemsTab = [];


echo "<?php\n";
?>

use elitedivision\amos\core\helpers\Html;
use elitedivision\amos\core\forms\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use elitedivision\amos\core\forms\Tabs;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var <?= ltrim($generator->modelClass, '\\') ?> $model
* @var yii\widgets\ActiveForm $form
*/


?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); <?= " ?>" ?>       


    <?php foreach ($generator->getFormTabsAsArray() as $tabName) { ?>

        <?php $tabNameSanitize = Inflector::slug($tabName); ?>

        <?= "<?php " ?> $this->beginBlock('<?= $tabNameSanitize ?>');<?= " ?>" ?>

        <?php foreach ($generator->getAttributesTab($tabName) as $attribute) { ?>

            <?php
            $column = $model->getTableSchema()->getColumn($attribute);
            if (!get_class($column) || $column == null) {
                continue;
            }
            ?>
            <div class="col-lg-6 col-sm-6">
                <?php
                $prepend = $generator->prependActiveField($attribute, $model);
                $field = $generator->activeField($attribute, $model);
                $append = $generator->appendActiveField($attribute, $model);
                if ($prepend) {
                    echo "\n\t\t\t<?php " . $prepend . " ?>";
                }
                if ($field) {
                    echo "\n\t\t\t<?= " . $field . " ?>";
                }
                if ($append) {
                    echo "\n\t\t\t<?php " . $append . " ?>";
                }
                ?>

            </div>
        <?php } ?>
        <div class="clearfix"></div>
        <?= "<?php " ?> $this->endBlock('<?= $tabNameSanitize ?>');<?= " ?>" ?>


        <?= "<?php " ?>  $itemsTab[] = [
        'label' => Yii::t('<?= $generator->messageCategory ?>', '<?= $tabName ?> '),
        'content' => $this->blocks['<?= $tabNameSanitize ?>'],
        ];
        <?= " ?>" ?>
    <?php } ?>


    <?= "<?= " ?> Tabs::widget(
    [
    'encodeLabels' => false,
    'items' => $itemsTab
    ]
    );
    <?= " ?>" ?>
    <div id="form-actions" class="bk-btnFormContainer">

        <?= "<?= " ?>
        Html::a(Yii::t('<?= $generator->messageCategory ?>', 'Chiudi'), Url::previous(), [
        'class' => 'btn btn-warning'
        ]);
        <?= "?>\n\n" ?>
        <?= "<?= " ?> Html::submitButton($model->isNewRecord ?
        Yii::t('<?= $generator->messageCategory ?>', 'Inserisci') :
        Yii::t('<?= $generator->messageCategory ?>', 'Salva'), [
        'class' => $model->isNewRecord ?
        'btn btn-success' :
        'btn btn-primary'
        ]); <?= "?>\n\n" ?>                       

    </div>
    <?= "<?php " ?> ActiveForm::end(); <?= " ?>" ?>

</div>
