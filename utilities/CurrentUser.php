<?php

namespace elitedivision\amos\core\utilities;

use Yii;
use yii\base\Object;

class CurrentUser extends Object
{

    static public $user;
    static public $userIdentity;
    static public $userProfile;

    static public function getUser()
    {
        if (!isset(self::$user)) {
            self::$user = Yii::$app->getUser();
        }
        return self::$user;
    }

    static public function getUserIdentity()
    {
        if (!isset(self::$userIdentity)) {
            if (!self::getUser()->getIsGuest()) {
                self::$userIdentity = self::getUser()->getIdentity();
            }
        }

        return self::$userIdentity;
    }

    static public function getUserProfile()
    {
        if (!isset(self::$userProfile)) {
            if (!Yii::$app->getUser()->getIsGuest()) {
                self::$userProfile = self::getUserIdentity()->userProfile;
            }
        }

        return self::$userProfile;
    }

}