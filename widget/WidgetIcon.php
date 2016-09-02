<?php

namespace elitedivision\amos\core\widget;

use elitedivision\amos\core\helpers\Html;
use elitedivision\amos\core\icons\AmosIcons;

class WidgetIcon extends WidgetAbstract {

    private $url;
    private $post;
    private $icon;
    private $iconFramework;
    private $namespace;
    private $classLi = [];
    private $classA = [];
    private $classSpan = [];
    private $targetUrl = '';

    /**
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getPost() {
        return $this->post;
    }

    /**
     * @return mixed
     */
    public function setPost($post) {
        $this->post = $post;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getTargetUrl() {
        return $this->targetUrl;
    }

    /**
     * @param mixed $targetUrl
     */
    public function setTargetUrl($targetUrl) {
        $this->targetUrl = $targetUrl;
    }

    /**
     * @return mixed
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon) {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getIconFramework() {
        return $this->iconFramework;
    }

    /**
     * @param mixed $iconFramework
     */
    public function setIconFramework($iconFramework) {
        $this->iconFramework = $iconFramework;
    }

    /**
     * @return mixed
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    public function getClassLi() {
        return $this->classLi;
    }

    /**
     * @param array $classLi
     */
    public function setClassLi($classLi) {
        $this->classLi = $classLi;
    }

    /**
     * @return array
     */
    public function getClassA() {
        return $this->classA;
    }

    /**
     * @param array $classA
     */
    public function setClassA($classA) {
        $this->classA = $classA;
    }

    /**
     * @return array
     */
    public function getClassSpan() {
        return $this->classSpan;
    }

    /**
     * @param array $classSpan
     */
    public function setClassSpan($classSpan) {
        $this->classSpan = $classSpan;
    }

    public function init() {
        parent::init();
        $this->setIconFramework('am');
    }

    public function run() {
        if (\Yii::$app->getUser()->can($this->getWidgetPermission())) {
            return $this->getHtml();
        } else {
            return '';
        }
    }

    public function getOptions() {
        return [
            'isVisible' => $this->isVisible(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
            'code' => $this->getCode(),
            'moduleName' => $this->getModuleName(),
            'icon' => $this->getIcon(),
            'namespace' => $this->getNameSpace(),
            'iconFramework' => $this->getIconFramework(),
            'classSpan' => $this->getClassSpan(),
        ];
    }

    public function getHtml() {

        $classSpanStr = implode(' ', $this->classSpan);
        $classSpanLi = implode(' ', $this->classLi);
        $classSpanA = implode(' ', $this->classA);
        //$icon = AmosIcons::show("{$this->icon}", ['class' => 'bk-pluginIcon'], $this->getIconFramework());

        $userAgent = (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/') > -1 || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? 'ie' : '';
        $icon = '<div class="svg-container">
		<svg title="' . $this->description . '" role="img" class="svg-content" >
          <use xlink:href="/svg/icone' . $userAgent . '.svg#' . $this->icon . '"></use>
        </svg>   
	</div>';

        $html = "<li class=\"col-xs-6 col-sm-3 col-md-2 col-lg-1\" data-code=\"{$this->code}\" data-module-name=\"{$this->moduleName}\">
                    <a href=\"{$this->url}\" target=\"{$this->targetUrl}\" title=\"{$this->description}\" role=\"menuitem\" id=\"sortableOpt1\">
                    <span class=\"{$classSpanStr}\">{$icon}
                        <span class=\"icon-dashboard-name pluginName\">{$this->label}</span>
                    </span>
                 </a>
            </li>";
        if (count($this->post) > 0) {
            $urlAssoluto = \Yii::$app->urlManager->createAbsoluteUrl([$this->post['action']]);           
            $html1 = "<form method=\"POST\" action=\"{$this->post['action']}\" target=\"_blank\">";
            $html2 = "";            
            foreach ($this->post['input'] as $input) {
                $html2 .= '<input ' . $input . '>';
            }
            $html3 = "<li class=\"col-xs-6 col-sm-3 col-md-2 col-lg-1\" data-code=\"{$this->code}\" data-module-name=\"{$this->moduleName}\">
                    <a href=\"{$this->post['submit']}\" target=\"{$this->targetUrl}\" title=\"{$this->description}\" role=\"menuitem\" id=\"sortableOpt1\">
                   <button {$this->post['submit']} class=\"{$classSpanStr}\" >{$icon}
                        <span class=\"icon-dashboard-name pluginName\">{$this->label}</span>
                    </button>
                 </a>
            </li>";
            $html4 = "</form>";
            $html = $html1 . $html2 . $html3 . $html4;
        }

        return $html;
    }

}
