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


<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#tab-1" role="tab" aria-selected="true">Общая информация</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#tab-2" role="tab" aria-selected="false">Переводы</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#tab-3" role="tab" aria-selected="false">Характеристики</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#tab-4" role="tab" aria-selected="false">Настройки фото</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-four-tabContent">
            <div class="tab-pane fade active show" id="tab-1" role="tabpanel">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'title',
                        'path',
                        'table',
                        'template',
                        'pagination',
                        'create_user',
                        'create_date',
                        'update_user',
                        'update_date',
                    ],
                ]) ?>

                <?= Html::a('Редактировать', ['block/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            </div>
            <div class="tab-pane fade" id="tab-2" role="tabpanel">

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
            <div class="tab-pane fade" id="tab-3" role="tabpanel">

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
                                return $model->public ? '<span class="badge badge-success">Да</span>' : '<span class="badge">Нет</span>';
                            }
                        ],
                        [
                            'attribute' => 'multi',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'content' => static function(BlockProp $model) {
                                return $model->multi ? '<span class="badge badge-success">Да</span>' : '<span class="badge">Нет</span>';
                            }
                        ],
                        [
                            'attribute' => 'required',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'content' => static function(BlockProp $model) {
                                return $model->required ? '<span class="badge badge-success">Да</span>' : '<span class="badge">Нет</span>';
                            }
                        ],
                        [
                            'attribute' => 'code',
                            'headerOptions' => ['style' => 'width:150px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                        ],
                        //'block_id',
                        [
                            'attribute' => 'sort',
                            'headerOptions' => ['style' => 'width:75px; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                        ],
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
            <div class="tab-pane fade" id="tab-4" role="tabpanel">

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
    <!-- /.card -->
</div>
