<?php

use thefx\blocks\models\BlockProperty;

/* @var $this yii\web\View */
/* @var $model BlockProperty */

$this->title = 'Добавить свойство';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['block/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="block-property-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
