<?php

namespace elitedivision\amos\core\widget;

use yii\base\Widget;

class WidgetAbstract extends Widget
{

    private $label;
    private $description;
    private $code;
    private $moduleName;
    private $widgetPermission = '????';


    public function isVisible()
    {
        if ($return = \Yii::$app->getUser()->can($this->getWidgetPermission())) {
            return true;
        } else {
            //pr($this->getWidgetPermission() ,'NON PRESENTE!');
            return false;
        }


    }

    public function get_real_class($obj)
    {
        $classname = get_class($obj);

        if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }

        return $classname;
    }

    protected function getPermissionsWidget()
    {
        return array('VIEW');
    }

    public function init()
    {
        parent::init();


        $auth = \Yii::$app->authManager;
        $WidgetName = $this->get_real_class($this);

        $permissionWidgetName = strtoupper($WidgetName) . '_VIEW';

        $this->setWidgetPermission($permissionWidgetName);
        /*
                if (strlen($WidgetName)) {
                    foreach ($this->getPermissionsWidget() as $permissionWidget) {
                        $permissionWidgetName = strtoupper($WidgetName) . '_' . $permissionWidget;

                        $this->setWidgetPermission($permissionWidgetName);

                        if (!$auth->getPermission($permissionWidgetName)) {
                            $Perm = $auth->createPermission($permissionWidgetName);
                            $Perm->description = 'Permesso di ' . $permissionWidget . ' sul widget ' . $WidgetName;
                            $auth->add($Perm);

                            $roleEnabled = $auth->getRole('ADMIN');
                            if ($roleEnabled) {
                                $auth->addChild($roleEnabled, $Perm);
                            }
                            if(
                                strstr(strtolower($permissionWidgetName) , 'my') ||
                                strstr(strtolower($permissionWidgetName) , 'fulmine') ||
                                strstr(strtolower($permissionWidgetName) , 'discussioni') ||
                                strstr(strtolower($permissionWidgetName) , 'profile') ||
                                strstr(strtolower($permissionWidgetName) , 'competenze') ||
                                strstr(strtolower($permissionWidgetName) , 'utili') ||
                                strstr(strtolower($permissionWidgetName) , 'sconti') ||
                                strstr(strtolower($permissionWidgetName) , 'user') ||
                                strstr(strtolower($permissionWidgetName) , 'utili')
                            ){
                                $roleEnabled = $auth->getRole('UTENTE_BASE');
                                if ($roleEnabled) {
                                    $auth->addChild($roleEnabled, $Perm);
                                }
                            }
                        }
                    }

                }
        */
    }

    /**
     * @return string
     */
    public function getWidgetPermission()
    {
        return $this->widgetPermission;
    }

    /**
     * @param string $widgetPermission
     */
    public function setWidgetPermission($widgetPermission)
    {
        $this->widgetPermission = $widgetPermission;
    }

    /**
     * @return mixed
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param mixed $moduleName
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }


}