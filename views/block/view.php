<?php

use thefx\blocks\models\blocks\Block;
use thefx\blocks\models\blocks\BlockProp;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model Block */
/* @var ActiveDataProvider $propsDataProvider */
/* @var BlockProp $propsSearchModel */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="block-view">

    <div class="nav-tabs-custom goods-form">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab_main">Общая информация</a></li>
            <li><a data-toggle="tab" href="#tab_translate">Переводы</a></li>
            <li><a data-toggle="tab" href="#tab_properties">Характеристики</a></li>
            <li><a data-toggle="tab" href="#tab_settings">Настройки фото</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab_main" class="tab-pane active">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'title',
                        'path',
                        'table',
                        'template',
                        'pagination',
                        [
                            'attribute' => 'create_user',
                            'value' => static function(Block $model) {
                                return $model->createUser->username;
                            }
                        ],
                        'create_date',
                        [
                            'attribute' => 'update_user',
                            'value' => static function(Block $model) {
                                return $model->updateUser->username;
                            }
                        ],
                        'update_date',
                    ],
                ]) ?>

                <?= Html::a('Редактировать', ['block/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            </div>
            <div id="tab_translate" class="tab-pane">

                <?= DetailView::widget([
                    'model' => $model->translate,
                    'attributes' => [
//                        'id',
//                        'block_id',
                        'category',
                        'categories',
                        'block_item',
                        'blocks_item',
                        'block_create',
                        'block_update',
                        'block_delete',
                        'category_create',
                        'category_update',
                        'category_delete',
                    ],
                ]) ?>

                <?= Html::a('Редактировать', ['block-translate/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            </div>
            <div id="tab_properties" class="tab-pane">

                <p>
                    <?= Html::a('Добавить характеристику', ['block-prop/create', 'block_id' => $model->id], ['class' => 'btn btn-success']) ?>
                </p>

                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $propsDataProvider,
                    'filterModel' => $propsSearchModel,
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'headerOptions' => ['style' => 'width:85px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                        ],
                        [
                            'attribute' => 'title',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'content' => static function(BlockProp $model) {
                                return Html::a($model->title, ['block-prop/update', 'id' => $model->id], ['data-pjax' => '0']);
                            }
                        ],
                        [
                            'attribute' => 'type',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'content' => static function(BlockProp $model) {
                                return $model->getTypeName();
                            }
                        ],
                        [
                            'attribute' => 'public',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'content' => static function(BlockProp $model) {
                                return $model->public ? '<span class="label label-success">Да</span>' : '<span class="label label-default">Нет</span>';
                            }
                        ],
                        [
                            'attribute' => 'multi',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'content' => static function(BlockProp $model) {
                                return $model->multi ? '<span class="label label-success">Да</span>' : '<span class="label label-default">Нет</span>';
                            }
                        ],
                        [
                            'attribute' => 'required',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'content' => static function(BlockProp $model) {
                                return $model->required ? '<span class="label label-success">Да</span>' : '<span class="label label-default">Нет</span>';
                            }
                        ],
                        [
                            'attribute' => 'code',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                        ],
                        //'block_id',
                        //'sort',
                        //'code',
                        //'in_filter',
                        //'hint',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['style' => 'width:50px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'controller' => 'block-prop',
                            'template' => '{update}{delete}',
                        ],
                    ],
                ]) ?>
                <?php Pjax::end(); ?>

            </div>
            <div id="tab_settings" class="tab-pane">

                <?= DetailView::widget([
                    'model' => $model->settings,
                    'attributes' => [
                        'upload_path',
                        'photo_crop_width',
                        'photo_crop_height',
                        'photo_crop_type',
                        'photo_preview_crop_width',
                        'photo_preview_crop_height',
                        'photo_preview_crop_type',
                    ],
                ]) ?>

                <?= Html::a('Редактировать', ['block-settings/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            </div>
        </div>
    </div>

</div>
