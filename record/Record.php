<?php

namespace elitedivision\amos\core\record;

use elitedivision\amos\core\behaviors\SoftDeleteByBehavior;
use elitedivision\amos\core\behaviors\VersionableBehaviour;
use backend\modules\admin\models\UserProfile;
use bedezign\yii2\audit\AuditTrailBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Record extends ActiveRecord {

    public static function find() {
        $return = parent::find();
        if (in_array('deleted_at', parent::attributes())) {
            $tableName = static::getTableSchema()->name;
            $return->andWhere([$tableName . '.deleted_at' => null]);
        }
        return $return;
    }

    public function behaviors() {
        return [
            [
                'class' => SoftDeleteByBehavior::className()
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => VersionableBehaviour::className(),
                'versionTable' => "{$this->tableName()}_version"
            ],
            [
                'class' => AuditTrailBehavior::className(),
            ]
        ];
    }

    public function representingColumn() {
        return null;
    }

    public function __toString() {

        $representingColumn = $this->representingColumn();
        if (($representingColumn === null) || ($representingColumn === array()))
            if ($this->getTableSchema()->primaryKey !== null) {
                $representingColumn = $this->getTableSchema()->primaryKey;
            } else {
                $columnNames = $this->getTableSchema()->getColumnNames();
                $representingColumn = $columnNames[0];
            }

        if (is_array($representingColumn)) {
            $part = '';
            foreach ($representingColumn as $representingColumn_item) {
                $part .= ($this->$representingColumn_item === null ? '' : $this->$representingColumn_item) . ' ';
            }
            return substr($part, 0, -1);
        } else {
            return $this->$representingColumn === null ? '' : (string) $this->$representingColumn;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedUserProfile() {
        return $this->hasOne(UserProfile::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedUserProfile() {
        return $this->hasOne(UserProfile::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedUserProfile() {
        return $this->hasOne(UserProfile::className(), ['id' => 'deleted_by']);
    }

    public function checkCodiceFiscale($attribute, $params) {
        $codiceFiscale = $this->$attribute;
        if (!$codiceFiscale) {
            $isValid = true;
        } // se non può essere null se ne deve occupare qualcun altro
        if (strlen($codiceFiscale) != 16) {
            $isValid = false;
        }
        $codiceFiscale = strtoupper($codiceFiscale);
        if (!ereg("^[A-Z0-9]+$", $codiceFiscale)) {
            $isValid = false;
        }
        $s = 0;
        for ($i = 1; $i <= 13; $i += 2) {
            $c = $codiceFiscale[$i];
            if ('0' <= $c && $c <= '9')
                $s += ord($c) - ord('0');
            else
                $s += ord($c) - ord('A');
        }
        for ($i = 0; $i <= 14; $i += 2) {
            $c = $codiceFiscale[$i];
            switch ($c) {
                case '0':
                    $s += 1;
                    break;
                case '1':
                    $s += 0;
                    break;
                case '2':
                    $s += 5;
                    break;
                case '3':
                    $s += 7;
                    break;
                case '4':
                    $s += 9;
                    break;
                case '5':
                    $s += 13;
                    break;
                case '6':
                    $s += 15;
                    break;
                case '7':
                    $s += 17;
                    break;
                case '8':
                    $s += 19;
                    break;
                case '9':
                    $s += 21;
                    break;
                case 'A':
                    $s += 1;
                    break;
                case 'B':
                    $s += 0;
                    break;
                case 'C':
                    $s += 5;
                    break;
                case 'D':
                    $s += 7;
                    break;
                case 'E':
                    $s += 9;
                    break;
                case 'F':
                    $s += 13;
                    break;
                case 'G':
                    $s += 15;
                    break;
                case 'H':
                    $s += 17;
                    break;
                case 'I':
                    $s += 19;
                    break;
                case 'J':
                    $s += 21;
                    break;
                case 'K':
                    $s += 2;
                    break;
                case 'L':
                    $s += 4;
                    break;
                case 'M':
                    $s += 18;
                    break;
                case 'N':
                    $s += 20;
                    break;
                case 'O':
                    $s += 11;
                    break;
                case 'P':
                    $s += 3;
                    break;
                case 'Q':
                    $s += 6;
                    break;
                case 'R':
                    $s += 8;
                    break;
                case 'S':
                    $s += 12;
                    break;
                case 'T':
                    $s += 14;
                    break;
                case 'U':
                    $s += 16;
                    break;
                case 'V':
                    $s += 10;
                    break;
                case 'W':
                    $s += 22;
                    break;
                case 'X':
                    $s += 25;
                    break;
                case 'Y':
                    $s += 24;
                    break;
                case 'Z':
                    $s += 23;
                    break;
            }
        }
        if (isset($codiceFiscale[15])) {

            if (chr($s % 26 + ord('A')) != $codiceFiscale[15]) {
                $isValid = false;
            } else {
                $isValid = true;
            }
        }
        if (!$isValid) {
            $this->addError($attribute, \Yii::t('app', 'Il codice fiscale non è un formato consentito'));
        }
    }

}
