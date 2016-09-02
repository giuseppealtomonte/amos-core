<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace elitedivision\amos\core\toolbar;

use Yii;
use yii\bootstrap\NavBar as YiiNavBar;

class NavBar extends YiiNavBar
{
    /*
     * eliminato il bottone di visualizzazione menu su mobile
     */
    protected function renderToggleButton()
    {
        return '';
    }
}
