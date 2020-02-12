<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\shop\entities\Block\BlockProp */

$this->title = 'Добавить свойство';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['block/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-prop-create">

    <?= $this->render('_form', [
        'model' => $model,
        'elem' => $elem,
    ]) ?>

</div>
