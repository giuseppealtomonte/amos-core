<?php

namespace elitedivision\amos\core\forms\editors;

use elitedivision\amos\core\views\AmosGridView;
use elitedivision\amos\core\views\grid\ActionColumn;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ContattiWidget extends Widget {

    //model di partenza
    public $model = null;
    //id del model record
    public $modelId = null;
    public $modelData = null;
    public $modelDataArr = null;
    public $permissions = [
        'add' => null
    ];
    //url della pagina di associazione
    public $targetUrl = null;
    //model di scelta associazioni
    public $modelTarget = null;
    //classe di ricerca per il model
    public $modelTargetSearch = [];
    public $modelTargetData = null;
    public $layout = null;
    public $layoutMittente = "{toolbarMittente}\n{itemsMittente}\n{footerMittente}";
    public $layoutTarget = "{toolbarTarget}\n{hiddenInputTarget}\n{itemsTarget}\n{footerTarget}";
    //variabili usate per identificare gli oggetti da salvare nel target
    public $postName = "UserProfile";
    public $postKey = "contatti";

    protected function throwErrorMessage($field) {
        return \Yii::t('app', 'Configurazione widget non corretta, {campo} mancante', [
                    'campo' => $field
        ]);
    }

    public function init() {
        parent::init();
        $this->layout = $this->layoutMittente;
        //se sono il widget di destinazione per le scelte delle associazioni
        if (!$this->targetUrl) {
            $this->layout = $this->layoutTarget;

            if (!$this->modelTargetSearch) {
                throw new InvalidConfigException($this->throwErrorMessage('modelTargetSearch'));
            }

            if (!$this->modelTargetSearch['class']) {
                throw new InvalidConfigException($this->throwErrorMessage('modelTargetSearch[class]'));
            }
            if (!$this->modelTargetSearch['action']) {
                throw new InvalidConfigException($this->throwErrorMessage('modelTargetSearch[action]'));
            }

            $this->modelTarget = \Yii::createObject($this->modelTargetSearch['class']);

            $this->modelTargetData = $this->modelTarget->{$this->modelTargetSearch['action']}(\Yii::$app->request->getQueryParams());
        }

        if (!$this->modelData) {
            throw new InvalidConfigException($this->throwErrorMessage('modelData'));
        }

        if (!$this->modelId) {
            throw new InvalidConfigException($this->throwErrorMessage('modelId'));
        }

        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }

        $this->modelDataArr = ArrayHelper::map($this->modelData->all(), 'id', 'id');
    }

    public function run() {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $this->layout);

        return $content;
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name) {
        switch ($name) {
            case '{toolbarMittente}':
                return $this->renderToolbarMittente();
            case '{itemsMittente}':
                return $this->renderItemsMittente();
            case '{toolbarTarget}':
                return $this->renderToolbarTarget();
            case '{hiddenInputTarget}':
                return $this->renderHiddenInputTarget();
            case '{itemsTarget}':
                return $this->renderItemsTarget();
            case '{footerTarget}':
                return $this->renderFooterTarget();
            case '{footerMittente}':
                return $this->renderFooterMittente();
            default:
                return false;
        }
    }

    /**
     * Renders the data models for the grid view.
     */
    public function renderItemsMittente() {
        return AmosGridView::widget([
                    'dataProvider' => new \yii\data\ActiveDataProvider([
                        'query' => $this->modelData
                            ]),
                    'columns' => [
                        /* [
                          'class' => 'yii\grid\SerialColumn',
                          ], */
                        'userprofile.avatar_id' =>
                        [
                            'label' => '',
                            'format' => 'html',
                            'value' => function ($model) {

                                $mediafile = \pendalf89\filemanager\models\Mediafile::findOne($model['avatar_id']);
                                $url = '/img/defaultProfilo.jpg';
                                if ($mediafile) {
                                    $url = $mediafile->getThumbUrl('medium');
                                }
                                return Html::img($url, ['width' => '100']);
                            }
                                ],
                                'nome',
                                'cognome',
                                ['attribute' => 'data_richiesta', 'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s']],
                                ['attribute' => 'data_accettazione', 'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s']],
                                [
                                    'attribute' => 'descrizione',
                                    'label' => 'Stato',
                                ],
                                [
                                    'class' => ActionColumn::className(),
                                    'template' => '{accetta} {rifiuta} {annulla} {termina}',
                                    'buttons' => [
                                        'accetta' => function ($url, $model) {
                                            $url = \yii\helpers\Url::current();
                                            if (\Yii::$app->getUser()->can($this->permissions['add'], ['model' => $this->model]) && $this->model['id'] != $model['id_richiedente'] && $this->model['id'] == $model['id_destinatario'] && $model['descrizione'] == 'In attesa di accettazione') {
                                                return Html::a('<p class="btn bk-btnAssociaCondominio" title="Accetta richiesta di contatto"><span id="bk-btnAssociaCondominio" class="ti ti-user"></span></p>', Yii::$app->urlManager->createUrl(['/admin/user-profile/cambia-stato-contatto', 'id' => $this->model['id'], 'azione' => 'accetta', 'url' => $url]), [
                                                            'title' => Yii::t('yii', 'Accetta richiesta di contatto')
                                                ]);
                                            } else {
                                                return '';
                                            }
                                        },
                                                'rifiuta' => function ($url, $model) {
                                            $url = \yii\helpers\Url::current();
                                            if (\Yii::$app->getUser()->can($this->permissions['add'], ['model' => $this->model]) && $this->model['id'] != $model['id_richiedente'] && $this->model['id'] == $model['id_destinatario'] && $model['descrizione'] == 'In attesa di accettazione') {
                                                return Html::a('<p class="btn bk-btnAssociaCondominio" title="Rifiuta richiesta di contatto"><span id="bk-btnAssociaCondominio" class="ti ti-trash"></span></p>', Yii::$app->urlManager->createUrl(['/admin/user-profile/cambia-stato-contatto', 'id' => $this->model['id'], 'azione' => 'rifiuta', 'url' => $url]), [
                                                            'title' => Yii::t('yii', 'Rifiuta richiesta di contatto')
                                                ]);
                                            } else {
                                                return '';
                                            }
                                        },
                                                'annulla' => function ($url, $model) {
                                            $url = \yii\helpers\Url::current();
                                            if (\Yii::$app->getUser()->can($this->permissions['add'], ['model' => $this->model]) && $this->model['id'] == $model['id_richiedente'] && $this->model['id'] != $model['id_destinatario'] && $model['descrizione'] == 'In attesa di accettazione') {
                                                return Html::a('<p class="btn bk-btnAssociaCondominio" title="Annulla richiesta di contatto"><span id="bk-btnAssociaCondominio" class="ti ti-trash"></span></p>', Yii::$app->urlManager->createUrl(['/admin/user-profile/cambia-stato-contatto', 'id' => $this->model['id'], 'azione' => 'annulla', 'url' => $url]), [
                                                            'title' => Yii::t('yii', 'Annulla richiesta di contatto')
                                                ]);
                                            } else {
                                                return '';
                                            }
                                        },
                                                'termina' => function ($url, $model) {
                                            $url = \yii\helpers\Url::current();
                                            if (\Yii::$app->getUser()->can($this->permissions['add'], ['model' => $this->model]) && ($this->model['id'] == $model['id_richiedente'] || $this->model['id'] == $model['id_destinatario']) && $model['descrizione'] == 'Attivo') {
                                                return Html::a('<p class="btn bk-btnAssociaCondominio" title="Termina contatto"><span id="bk-btnAssociaCondominio" class="ti ti-unlink"></span></p>', Yii::$app->urlManager->createUrl(['/admin/user-profile/cambia-stato-contatto', 'id' => $this->model['id'], 'azione' => 'termina', 'url' => $url]), [
                                                            'title' => Yii::t('yii', 'Termina contatto')
                                                ]);
                                            } else {
                                                return '';
                                            }
                                        },
                                            ],
                                        ],
                                    ]
                        ]);
                    }

                    /**
                     * Renders the data models for the grid view.
                     */
                    public function renderItemsTarget() {
                        $this->getView()->params['modelDataArr'] = $this->modelDataArr;
                        $this->getView()->params['modelTargetData'] = $this->modelTargetData;
                        $this->getView()->params['postKey'] = $this->postKey;
                        $this->getView()->params['postName'] = $this->postName;
                        $idConnesso = Yii::$app->getUser()->getId();
                        $ruoloConnesso = Yii::$app->authManager->getRolesByUser($idConnesso);                        
                        if ($idConnesso == $this->model->id || (isset($ruoloConnesso['ADMIN']) || isset($ruoloConnesso['CL_SMART']))) {
                            $Grid = $this->getView()->renderFile('@backend/modules/admin/views/user-profile/associa_contatti_grid.php', [
                                'searchModel' => $this->modelTarget
                            ]);
                            return $Grid;
                        } else {
                            echo '<div class="site-error">
                                        <div class="alert alert-danger">' .
                                        Yii::t('contattiWidget', 'Non sei autorizzato ad eseguire questa operazione.')
                                    . '</div>   
                                    </div>';
                        }
                    }

                    /**
                     * Renders the toolbar
                     */
                    public function renderToolbarMittente() {
                        $buttons = '';
                        if (\Yii::$app->getUser()->can($this->permissions['add'], ['model' => $this->model])) {
                            $buttons = Html::a('Associa', [$this->targetUrl, 'id' => $this->modelId], [
                                        'class' => 'btn btn-primary btn-associa',
                            ]);
                        }
                        return Html::tag('div', $buttons, ['class' => 'row']);
                    }

                    /**
                     * Renders the toolbar
                     */
                    public function renderToolbarTarget() {
                        $idConnesso = Yii::$app->getUser()->getId();
                        $ruoloConnesso = Yii::$app->authManager->getRolesByUser($idConnesso);                        
                        if ($idConnesso == $this->model->id || (isset($ruoloConnesso['ADMIN']) || isset($ruoloConnesso['CL_SMART']))) {
                        $buttons = Html::submitButton('Salva', [
                                    'class' => 'btn btn-primary btn-associa-salva']);

                        return Html::beginForm() . Html::tag('div', $buttons, ['class' => 'row']);
                        }
                    }

                    /**
                     * Renders the toolbar
                     */
                    public function renderFooterTarget() {
                        $idConnesso = Yii::$app->getUser()->getId();
                        $ruoloConnesso = Yii::$app->authManager->getRolesByUser($idConnesso);                        
                        if ($idConnesso == $this->model->id || (isset($ruoloConnesso['ADMIN']) || isset($ruoloConnesso['CL_SMART']))) {
                        return Html::endForm();
                        }
                    }

                    /**
                     * Renders the input hidden section
                     */
                    public function renderHiddenInputTarget() {
                        $hiddenInputSection = "";

                        foreach ($this->modelDataArr as $id => $label) {
                            $hiddenInputSection .= Html::tag("input", null, ['value' => $id, 'type' => 'hidden', 'name' => $this->postName . '[' . $this->postKey . '][]']);
                        }

                        return Html::tag('div', $hiddenInputSection, ['class' => 'hiddenInputContainer']);
                    }

                    /**
                     * Renders the toolbar
                     */
                    public function renderFooterMittente() {
                        return '';
                    }

                }
                