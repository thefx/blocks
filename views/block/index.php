<?php

use thefx\blocks\forms\search\BlockSearch;
use thefx\blocks\models\blocks\Block;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel BlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разделы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="block-index">

<!--    --><?php //Pjax::begin(); ?>

    <p>
        <?= Html::a('Добавить блок', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width:85px; text-align:center'],
                'contentOptions' => ['style' => 'width:85px; text-align:center'],
            ],
            [
                'attribute' => 'title',
                'format' => 'html',
                'value' => static function(Block  $model) {
                    return Html::a($model->title, ['block/view', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'sort',
                'headerOptions' => ['style' => 'width:85px; text-align:center'],
                'contentOptions' => ['style' => 'width:85px; text-align:center'],
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {delete}',
                'contentOptions' => ['style' => 'width:85px; text-align:center'],
                'urlCreator' => static function ($action, Block $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
//            'table',
//            'template',
            //'pagination',
            //'create_user',
            //'create_date',
            //'update_user',
            //'update_date',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]) ?>

<!--    --><?php //Pjax::end(); ?>

</div>
