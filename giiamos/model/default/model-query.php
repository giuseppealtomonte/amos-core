<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use backend\modules\cwh\query\CwhActiveQuery;
use creocoder\taggable\TaggableQueryBehavior;
use yii\helpers\ArrayHelper;


/**
 * Class <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>Query
 * @package <?= $generator->ns ?>
 * File generato automaticamente, verificarne
 * il contenuto prima di utilizzarlo in produzione
 */
class <?=str_replace(' ', '', trim(Inflector::camel2words(StringHelper::basename($generator->modelClass))))?>Query extends CwhActiveQuery
{
    /**
     * @return array
     * da scommentare se si utilizzano i tag
     */
    //public function behaviors()
    //{
    //    return ArrayHelper::merge(
    //        parent::behaviors(), [
    //            TaggableQueryBehavior::className()
    //        ]
    //    );   
    //}

    /**
     * @return \yii\db\ActiveQuery     
     */
    public function attive()
    {
    //Questo è solo un esempio, verificare che i campi e le tabelle siano corretti
    return $this->innerJoin('<?= lcfirst(str_replace(' ', '', trim(Inflector::camel2words(StringHelper::basename($generator->modelClass))))) ?>_stato', '<?= lcfirst(str_replace(' ', '', trim(Inflector::camel2words(StringHelper::basename($generator->modelClass))))) ?>.<?= lcfirst(str_replace(' ', '', trim(Inflector::camel2words(StringHelper::basename($generator->modelClass))))) ?>_stato_id = <?= lcfirst(str_replace(' ', '', trim(Inflector::camel2words(StringHelper::basename($generator->modelClass))))) ?>_stato.id AND <?= lcfirst(str_replace(' ', '', trim(Inflector::camel2words(StringHelper::basename($generator->modelClass))))) ?>_stato.nome = :stato_nome', [
            ':stato_nome' => 'Attiva'
        ]);
    }
}