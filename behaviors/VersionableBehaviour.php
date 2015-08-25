<?php

namespace elitedivision\amos\core\behaviors;


use yii\base\Behavior;
use yii\base\Exception;

class VersionableBehaviour extends Behavior
{

    public $versionAttribute = 'version';
    public $versionTable = null;

    public function init()
    {
        parent::init();

        if (!$this->versionTable) {
            throw new Exception(\Yii::t('app', 'Version table not defined'));
        }


    }

}