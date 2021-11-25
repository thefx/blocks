<?php

use thefx\blocks\models\blocks\BlockSettings;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model BlockSettings */

$this->title = 'Create Block Settings';
$this->params['breadcrumbs'][] = ['label' => 'Block Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
