<?php

use thefx\blocks\models\Block;

/* @var $this yii\web\View */
/* @var $model Block */

$this->title = 'Редактировать блок';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
