<?php

/** @var string $link */
/** @var string $data_key */
/** @var string $deleteLink */

?>

<div class="mb-10 mr-10 thumbnail pull-left" style="" data-key="<?= $data_key ?>">
    <a class="btn-delete"
       href="<?= $deleteLink ?>"
       title="Удалить изображение"
       data-confirm="Удалить изображение?"
       data-method="post">
        <i class="fa fa-times-circle"></i>
    </a>
    <div class="image">
        <img src="<?= $link ?>" alt="">
    </div>
<!--    <div class="caption">-->
<!--        --><?//= Html::submitButton('<span class="fa- fa-trash- text-danger">Удалить</span>', [
//            'class' => 'btn btn-xs',
//            'data-confirm' => 'Удалить файл?',
//            'attr' => $attr
//        ]) ?>
<!--    </div>-->
</div>
