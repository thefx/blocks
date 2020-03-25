<?php

use thefx\blocks\forms\search\BlockPropElemSearch;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel BlockPropElemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Block Prop Elems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-prop-elem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Block Prop Elem', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'block_prop_id',
            'title',
            'code',
            'sort',
            //'default',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
