<?php

/** @var string $link */
/** @var string $data_key */
/** @var string $deleteLink */

?>

<div class="mr-2 mb-2 thumbnail pull-left" style="" data-key="<?= $data_key ?>">
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
</div>
