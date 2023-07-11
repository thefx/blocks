<?php

use thefx\blocks\models\forms\search\BlockItemSearch;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel BlockItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Block Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Block Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'alias',
            'anons',
            'text:ntext',
            //'photo',
            //'photo_preview',
            //'date',
            //'section_id',
            //'public',
            //'sort',
            //'create_user',
            //'create_date',
            //'update_user',
            //'update_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
