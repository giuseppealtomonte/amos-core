<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace elitedivision\amos\core\giiamos\crud;

use Yii;
use yii\db\ActiveRecord;

class Generator extends \schmunk42\giiant\generators\crud\Generator
{
    public $formTabs;
    public $formTabsSeparator = '|';
    public $formTabsFieldSeparator = ',';
    public $tabsFieldList = [];
    public $providerList = 'elitedivision\amos\core\giiamos\crud\providers\CallbackProvider,
                            elitedivision\amos\core\giiamos\crud\providers\DateTimeProvider,
                            elitedivision\amos\core\giiamos\crud\providers\EditorProvider,
                            elitedivision\amos\core\giiamos\crud\providers\OptsProvider,
                            elitedivision\amos\core\giiamos\crud\providers\RelationProvider';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Amos CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'Questo generatore permette di creare CRUD (Create, Read, Update, Delete) su model specifici';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['formTabs'], 'filter', 'filter' => 'trim'],
            ['tabsFieldList', 'checkIsArray']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'formTabs' => 'Tabs',
        ]);
    }

    public function checkIsArray()
    {
        if (!is_array($this->tabsFieldList)) {
            $this->addError('tabsFieldList', 'tabsFieldList is not array!');
        }
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'formTabs' => 'Elenco delle tab da creare sulla form <code>tab1|tab2|...</code>.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), []);
    }

    /**
     * Generates parameter tags for phpdoc
     * @return array parameter tags for phpdoc
     */
    public function generateActionParamComments()
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (($table = $this->getTableSchema()) === false) {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . (substr(strtolower($pk), -2) == 'id' ? 'integer' : 'string') . ' $' . $pk;
            }

            return $params;
        }
        if (count($pks) === 1) {
            return ['@param ' . $table->columns[$pks[0]]->phpType . ' $id'];
        } else {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . $table->columns[$pk]->phpType . ' $' . $pk;
            }

            return $params;
        }
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return boolean|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }

    /**
     * @return array model column names
     */
    public function getFormTabsAsArray()
    {
        $formTabsAsArray = [];
        if ($this->formTabs) {
            $formTabsAsArray = explode($this->formTabsSeparator, $this->formTabs);
        }

        if (!count($formTabsAsArray)) {
            $formTabsAsArray = ['dettagli'];
        }

        return $formTabsAsArray;

    }


    /**
     * @return array model column names
     */
    public function getAttributesTab($tabCode)
    {
        $attributes = [];
        if ($this->tabsFieldList && array_key_exists($tabCode, $this->tabsFieldList)) {
            if ($this->tabsFieldList[$tabCode]) {
                $attributes = explode($this->formTabsFieldSeparator, $this->tabsFieldList[$tabCode]);
            }
        } else {
            $attributes = $this->safeAttributes();
        }
        return $attributes;

    }
}
