<?php

namespace elitedivision\amos\core\views;

use elitedivision\amos\core\views\common\BaseListView;
use Yii;
use yii\helpers\Html;

class CalendarView extends BaseListView {

    public $events = [];   
    public $titolo = null;
    
    public $replace = [];
    
    public $layout = "{summary}\n{items}\n{pager}";       
    
    public $defaultClientOptions = [
    ];
    public $eventConfig = [
        'id' => 'id',
        'title' => 'titolo',
        'start' => 'data_inizio',
        'end' => 'data_fine',
        'color' => 'colore',
        'url' => 'url',
    ];

    public function setClientOptions(array $clientOptions) {
        $this->clientOptions = $clientOptions;
    }
        

    public function getClientOptions() {
        return $this->clientOptions;
    }

    public function setEvents(array $events) {
        $this->events = $events;
    }

    public function getEvents() {
        return $this->events;
    }

    public function initEvents($models) {

        $events = [];
               
        foreach ($models as $model) {
            $Event = new \yii2fullcalendar\models\Event();            
            foreach ($this->eventConfig as $kEvent => $vEvent) {               
                $Event->{$kEvent} = $model[$vEvent];
            }            
            $events[] = $Event;
        }        
        return $events;
    }        

    public function init() {
        parent::init();               
            
        $this->defaultClientOptions = [
            'lang' => Yii::$app->language == 'it-IT' ? 'it':  Yii::$app->language ,
        ];
        
        $this->setClientOptions(\yii\helpers\ArrayHelper::merge($this->defaultClientOptions, $this->getClientOptions()));
        
        $models = $this->dataProvider->getModels();
        $keys = $this->dataProvider->getKeys();        

        $this->setEvents($this->initEvents($models));         
    }

    public function run() { 
        return \yii2fullcalendar\yii2fullcalendar::widget([
                    'clientOptions' => $this->getClientOptions(),
                    'events' => $this->getEvents(),                
        ]);
    }

}
