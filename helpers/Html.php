<?php

namespace elitedivision\amos\core\helpers;


use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class Html extends \yii\helpers\Html
{

    public static function submitButton($content = 'Submit', $options = [], $permissions = null , $permissionsParams = [])
    {        
        if (isset($permissions)) {
            if (is_array($permissions)) {
                throw new InvalidConfigException("{permissions} must be set as String");
            }
            if (\Yii::$app->getUser()->can($permissions , $permissionsParams)) {

                return parent::submitButton($content, $options);
            } else {
                return '';
            }
        }
        return parent::submitButton($content, $options);
    }
    
    public static function button($content = 'Button', $options = [], $permissions = null , $permissionsParams = [])
    {        
        if (isset($permissions)) {
            if (is_array($permissions)) {
                throw new InvalidConfigException("{permissions} must be set as String");
            }
            if (\Yii::$app->getUser()->can($permissions , $permissionsParams)) {

                return parent::button($content, $options);
            } else {
                return '';
            }
        }
        return parent::button($content, $options);
    }

    public static function a($text, $url = null, $options = [])
    { 
        if (isset($url)) {
            $safeUrl = '';
            $safeUrlParams = [];
            if(is_array($url)){
                $tempUrl = $url;
                $safeUrl = $url[0];
                unset($tempUrl[0]);
                $safeUrlParams = $tempUrl;
            }else{
                $parsedUrl = parse_url($url);
                $safeUrl = $parsedUrl['path'];
                parse_str(isset($parsedUrl['query']) ? $parsedUrl['query'] : NULL, $safeUrlParams);
            }

            $isValidPermission = \Yii::$app->getAuthManager()->getPermission($safeUrl);

            if ($isValidPermission) {

                if (\Yii::$app->getUser()->can($safeUrl)) {

                    $paramsUrl = ArrayHelper::merge([0 => $safeUrl], $safeUrlParams);
                    
                    return parent:: a($text, $paramsUrl, $options);
                } else {                    
                        return '';                  
                }
            } else {
                
                return parent:: a($text, $url, $options);
            }
        }
        
        return parent:: a($text, $url, $options);
    }

}
/*
public static function a($text, $url = null, $options = [])
    {
        if ($url !== null) {
            $options['href'] = Url::to($url);
        }
        return static::tag('a', $text, $options);
    }*/