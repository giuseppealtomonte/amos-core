<?php

namespace elitedivision\amos\core\rbac;

use yii\rbac\Rule;

class UpdateOwnUserProfile extends Rule {

    public $name = 'isYourProfile';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params) {
        if ($params['model']) {
            return isset($params['model']) ? $params['model']['id'] == $user : false;
        }
    }

}
