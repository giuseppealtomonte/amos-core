<?php

namespace elitedivision\amos\core\views;

use yii\grid\GridView;
use yii\helpers\Html;
use Yii;
use Closure;
use yii\i18n\Formatter;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\BaseListView;
use yii\base\Model;

class AmosGridView extends GridView {

    private $class_thead = '';
    public $title;
    public $name = 'grid';
    public $panelTemplate = <<< HTML
            {summary}
            {items}
            {pager}
        
HTML;
    public $layout = "<div class=\"table_switch table-responsive\"> {items} \n {summary} <br> {pager} </div>";
    public $summary = "Risultati visualizzati {count} - Risultati da {begin} a {end} su un totale di {totalCount} - Pagina {page} di {pageCount}";
    public $tableOptions = ['class' => 'table table-striped'];

    /* public function getLabel($column){
      return html_entity_decode(strip_tags($column->renderHeaderCell()));
      } */

    /**
     * Sovrascriviamo la funzione nativa per assegnare al tag TD l'attributo 'title' con valore prelevato dalla label dell'HeaderTable
     * Renders the filter.
     * @return string the rendering result.
     */
    public function renderFilters() {
        if ($this->filterModel !== null) {
            $cells = [];
            foreach ($this->columns as $column) {
                /* @var $column Column */
                $column->filterOptions['title'] = html_entity_decode(strip_tags($column->renderHeaderCell()));
                $cells[] = $column->renderFilterCell();
            }
            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        } else {
            return '';
        }
    }

    /**
     * Sovrascriviamo la funzione nativa per assegnare al tag TR la classe filters 
     * e a TD la classe input_element
     * stilizzata in modo tale da essere vista nel mobile a differenza del comportamento base
     * della tabella responsiva
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableHeader() {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $column->headerOptions['title'] = html_entity_decode(strip_tags($column->renderHeaderCell()));

            //if there is a input add class filters to TR and input_element to TD
            if (strpos(strtolower($column->renderHeaderCell()), "<input") !== false) {
                $this->headerRowOptions['class'] = 'filters';
                $column->headerOptions['class'] = 'input_element';
            }
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition == self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition == self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
            $class_thead = 'thead_static';
        }
        return "<thead class='" . $class_thead . "'>\n" . $content . "\n</thead>";
    }

    /**
     * Sovrascriviamo la funzione nativa per assegnare al tag TD l'attributo 'title' con valore prelevato dalla label dell'HeaderTable
     * Renders a table row with the given data model and key.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderTableRow($model, $key, $index) {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
            //remove html tag and decode html entity    		
            $column->contentOptions['title'] = html_entity_decode(strip_tags($column->renderHeaderCell()));
            $cells[] = $column->renderDataCell($model, $key, $index);
        }
        if ($this->rowOptions instanceof Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }

        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

        return Html::tag('tr', implode('', $cells), $options);
    }

}
