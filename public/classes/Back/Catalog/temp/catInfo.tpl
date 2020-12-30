<?php
use Websm\Framework\Router\Router;
$rUpdateCatId = Router::byName('Catalog.updateCat');
?>
<div class="cat box">

    <?php
    $picture = $catalog->getPicture('150x150'); 
    $style = $picture ? 'background-image: url('.$picture.');' : '';
    ?>

    <div class="image inline" style="<?= $style; ?>" ></div>
    <div class="cat-body inline">
        <div class="title"><?= $catalog->title; ?></div>
        <div class="descr"><?= $catalog->preview ?: 'Нет краткого описания категории.'; ?></div>
    </div>
    <div class="actions inline">
        <a href="<?= $rUpdateCatId->getAbsolutePath(['id' => $catalog->id, 'cid' => $catalog->id]); ?>"
            onclick="catalog.moveTo(this.href); return false;"
            class="action edit inline"
            title="Редактировать категорию"></a>
    </div>
</div>
