<?php

use thefx\blocks\models\blocks\BlockProp;


/* @var $this yii\web\View */
/* @var $model BlockProp */

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
