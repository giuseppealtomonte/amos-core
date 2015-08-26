<?php

namespace elitedivision\amos\core\views;

use elitedivision\amos\core\views\common\BaseListView;
use Yii;

class ListView extends BaseListView
{
    public $name = 'list';

    public $layout = "{items}\n{pager}";

    public $template = '{view} {update} {delete}';
    public $buttons;
    public $buttonClass = 'elitedivision\amos\core\views\common\Buttons';
    public $viewOptions = [
        'class' => 'btn bk-btnMore'
    ];
    public $updateOptions = [
        'class' => 'btn bk-btnEdit'
    ];
    public $deleteOptions = [
        'class' => 'btn bk-btnDelete'
    ];

    public $_isDropdown = false;


}
