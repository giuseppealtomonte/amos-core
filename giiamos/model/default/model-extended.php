<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
/**
 * This is the template for generating the model class of a specified table.
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name => label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name => relation declaration)
 */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
//use backend\modules\cwh\behaviors\CwhNetworkBehaviors;
//use backend\modules\eventi\models\query\EventiQuery;
//use yii\helpers\ArrayHelper;

/**
* This is the model class for table "<?= $tableName ?>".
*/
class <?= $className ?> extends \<?= $generator->ns ?>\base\<?= $className . "\n" ?>
{
    //public $regola_pubblicazione;
    //public $destinatari;
    //public $validatori;
    
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }
    
   /**
    * Scommentare le seguenti function(), gli attributi sopra  e gli "use" 
    * nel caso il modulo necessiti di regole di pubblicazione e personalizzarle
    * a piacimento. E' necessario per poter utilizzare le regole di 
    * pubblicazione creare la classe <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>Query.php
    * che conterrà le query specifiche per gestire la pubblicazione
    * dei contenuti per differenti destinatari. 
    * Nelle function() sotto facciamo il merge con le altre function() 
    * eventualmente già presenti per il model
    */
    /*
    
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['regola_pubblicazione', 'destinatari', 'validatori'], 'safe'],
        ]);
    }
    
    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                    'tagValues' => '',
                    'regola_pubblicazione' => 'Pubblicata per',
                    'destinatari' => 'Per i condominii',
                ]);
    }
    
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'CwhNetworkBehaviors' => [
                'class' => CwhNetworkBehaviors::className(),
            ]
        ]);
    }
    
    public static function find()
    {
        $<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>Query = new <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>Query(get_called_class());
        $<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>Query->andWhere('<?= lcfirst(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>.deleted_at IS NULL');
        return $<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>Query;
    }   
    */
    
     /**
     * Restituisce il percorso del marker, da personalizzare a piacimento
     * @return string Il percorso del marker che sarà utilizzato nella mappa
     */
    public function getIconaMarker(){
        return null;
    }
    
    /**
     * Restituisce il colore della categoria
     */
    public function getColoreCategoria() {
        return NULL; //da personalizzare
    }

    /**
     * Restituisce il nome della categoria per la legenda
     */
    public function getNomeLegenda() {
        return NULL; //da personalizzare
    }
    
    /**
     * Funzione che crea gli eventi da visualizzare sulla mappa in caso di più eventi legati al singolo model
     * Andrà valorizzato il campo array a true nella configurazione della vista calendario nella index
     */
    public function getEvents() {
        return NULL; //da personalizzare
    }
    
    /**
     * Restituisce l'url per il calendario dell'attività
     */
    public function getUrlEvento() {
        return NULL; //da personalizzare magari con Yii::$app->urlManager->createUrl([]);    
    }
    
    /**
     * Restituisce il colore associato all'evento
     */
    public function getColoreEvento() {
        return NULL; //da personalizzare     
    }
    
    /**
     * Restituisce il titolo, possono essere anche più dati, associato all'evento
     */
    public function getTitoloEvento() {
        return NULL; //da personalizzare     
    }
}
