<?php
namespace elitedivision\amos\core\formatter;

use yii\i18n\Formatter as YiiFormatter;

class Formatter extends YiiFormatter
{

    public $tagsSeparator;

    public function init()
    {
        parent::init();
        if ($this->tagsSeparator == null) {
            $this->tagsSeparator = ',';
        }
    }

    public function asImage()
    {
        //'class' => 'gridview-image'
    }

    public function asTags($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        $tagsValues = explode($this->tagsSeparator, $value);

        $tagFormatter = '';
        foreach ($tagsValues as $tag) {
            $tagFormatter .= '<span class="formatter-tag">' . $tag . '</span>';
        }

        return $tagFormatter;
    }

    public function asCarteCredito($value)
    {
        $dimvalue = strlen($value);
        $newvalue = "";
        for ($i = 0; $i < $dimvalue; $i = $i + 4) {
            $newvalue .= " " . $value[$i] . $value[$i + 1] . $value[$i + 2] . $value[$i + 3];
        }
        return $newvalue;
    }

    public function asMaiuscolo($value)
    {
        $newvalue = strtoupper($value);
        return $newvalue;
    }


    public function asStatoattivo($value)
    {
        if ($value == 0) {
            $visStato = "Non Attivo";
            return $visStato;
        } else if ($value == 1) {
            $visStato = "Attivo";
            return $visStato;
        } else {
            return $this->nullDisplay;
        }
    }

    public function asPrice($value)
    {
        $value = round($value, 2);
        $newvalue = "€ " . $value;
        return $newvalue;
    }

    public function asPercentuale($value)
    {
        $value = number_format($value, 2, '.', '');
        $newvalue = $value . " %";
        return $newvalue;
    }

    public function asStatoSiNo($value)
    {
        if ($value == 0) {
            $visStato = "No";
            return $visStato;
        } else if ($value == 1) {
            $visStato = "Si";
            return $visStato;
        } else {
            return $this->nullDisplay;
        }
    }

    private function formatDateDiff($start, $end = null)
    {
        if (!($start instanceof \DateTime)) {
            $start = new \DateTime($start);
        }

        if ($end === null) {
            $end = new \DateTime();
        }

        if (!($end instanceof \DateTime)) {
            $end = new  \DateTime($start);
        }

        $interval = $end->diff($start);

        $doPlural = function ($nb, $str) {
            switch ($str) {
                case "year":
                    $str = $nb > 1 ? "anni" : "anno";
                    break;
                case "month":
                    $str = $nb > 1 ? "mesi" : "mese";
                    break;
                case "day":
                    $str = $nb > 1 ? "giorni" : "giorno";
                    break;
                case "hour":
                    $str = $nb > 1 ? "ore" : "ora";
                    break;
                case "minute":
                    $str = $nb > 1 ? "minuti" : "minuto";
                    break;
                case "second":
                    $str = $nb > 1 ? "secondi" : "secondo";
                    break;

            }

            return $str . " fa";

        };
        /*
                $format = array();
                if ($interval->y !== 0) {
                    $format[] = "%y " . $doPlural($interval->y, "year");
                }
                if ($interval->m !== 0) {
                    $format[] = "%m " . $doPlural($interval->m, "month");
                }
        */
        if ($interval->d !== 0) {

            $datetime1 = new \DateTime($start->format("Y-m-d"));
            $datetime2 = new \DateTime($end->format("Y-m-d"));

            $interval2 = $datetime1->diff($datetime2);

            if ($interval2->d == 1) {
                return "ieri alle " . $start->format("H:i");
            } else {
                return $start->format("d/m/Y") . " alle " . $start->format("H:i");
            }

        }

        if ($interval->h !== 0) {

            $datetime1 = new \DateTime($start->format("Y-m-d"));
            $datetime2 = new \DateTime($end->format("Y-m-d"));

            $interval2 = $datetime1->diff($datetime2);

            if ($interval2->d == 1) {
                return "ieri alle " . $start->format("H:i");
            }

            if ($interval->h < 6) {
                $format[] = "%h " . $doPlural($interval->h, "hour");
            } else {
                return "oggi alle " . $start->format("H:i");
            }

        } elseif ($interval->i !== 0) {
            $format[] = "%i " . $doPlural($interval->i, "minute");
        } elseif ($interval->s >= 0) {
            return "adesso";
            /*
             * else {
                $format[] = "%s " . $doPlural($interval->s, "second");
            }
           */
        }

        if (count($format) > 1) {
            $format = array_shift($format);//. " and " . array_shift($format);
        } else {
            $format = array_pop($format);
        }

        return $interval->format($format);
    }

    public function asDateTime($value, $format = 'human')
    {
        if ($format == 'human') {

            $dStart = new \DateTime($value);
            $dEnd = new \DateTime();
            return $this->formatDateDiff($dStart, $dEnd);
        } else {
            parent::asDateTime($value, $format);
        }

    }
}