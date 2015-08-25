<?php

namespace elitedivision\amos\core\views;

use elitedivision\amos\core\record\Record;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\MapAsset;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use yii\widgets\ListView as BaseListView;

class MapView extends BaseListView
{

    public $name = 'map';

    /**
     * @var Map $map
     */
    public $map;

    public $zoom = 12;

    public $autoCenter = true;

    public $centerPoint = [
        'lat' => 41.9109,
        'lng' => 12.4818
    ];

    public $markers;

    public $markerConfig = [
        'lat' => 'lat',
        'lng' => 'lng',
    ];

    public function init()
    {
        parent::init();
        $LatLngCenter = new LatLng(['lat' => $this->centerPoint['lat'], 'lng' => $this->centerPoint['lng']]);
        $this->map = new Map([
            'zoom' => $this->zoom,
            'center' => $LatLngCenter,
            'width' => '100%',
        ]);

        $this->initMarkers();

        $this->pushMarkers();

        $this->flushMap();

    }

    public function initMarkers()
    {
        $models = $this->dataProvider->getModels();
        $keys = $this->dataProvider->getKeys();
        $markers = [];
        foreach (array_values($models) as $index => $model) {
            if ($marker = $this->getMarker($model, $keys[$index], $index)) {
                $markers[] = $marker;
            }
        }
        $this->markers = $markers;
    }

    public function flushMap()
    {
        if ($this->autoCenter) {
            if ($LatLonCenter = $this->map->getMarkersCenterCoordinates()) {
                $this->map->setCenter($LatLonCenter);
                $this->map->zoom = $this->map->getMarkersFittingZoom();
            }

        }
    }

    public function getMarker(Record $model, $key, $index)
    {
        $marker = null;
        if ($this->validateMarker($model, $key, $index)) {
            $LatLng = new LatLng([
                'lat' => $model[$this->markerConfig['lat']],
                'lng' => $model[$this->markerConfig['lng']]
            ]);

            $marker = new Marker([
                'position' => $LatLng,
                'title' => $model->__toString(),
            ]);

            $marker->attachInfoWindow(
                new InfoWindow([
                    'content' => $this->renderItem($model, $key, $index)
                ])
            );


        }
        return $marker;
    }


    public function validateMarker($model, $key, $index)
    {
        if (isset($model) && isset($model[$this->markerConfig['lat']]) && isset($model[$this->markerConfig['lng']])) {
            return true;
        }
        return false;
    }

    public function pushMarkers()
    {
        foreach ($this->markers as $marker) {
            $this->map->addOverlay($marker);
        }
    }

    public function run()
    {
        MapAsset::register($this->getView());
        return $this->map->display();
    }

}