<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockPropElem */

$this->title = 'Create Block Prop Elem';
$this->params['breadcrumbs'][] = ['label' => 'Block Prop Elems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-prop-elem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
