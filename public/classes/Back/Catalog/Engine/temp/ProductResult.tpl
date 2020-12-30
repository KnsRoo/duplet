<?php

use Core\Router\Router;
use Core\Misc\MiniPager\MiniPager;

$check = \Model\Catalog\Product::find(['id' => $product->id])->get();
$cid = $check->cid;

?>
<input type="checkbox" class="hidden preRemove" id="preRemove-product-<?= $product->id;?>" />
<div data-subscribe="cat" data-product="true" data-message='{"id": "<?= $product->id; ?>", "type": "product"}' class="product relative box <?= $product->visible ? '' : 'unvis';?> <?= ($product->id == $this->updateProduct) ? 'edit' : '';?>" data-link="object-<?= $product->id;?>">
    <div class="actions">

        <a href="catalog/cat-<?= $cid ? $cid : ''?>/update-product-<?= $product->id; ?>" onclick="catalog.moveTo(this.href); return false;" class="action inline edit" title="Редактировать товар"></a> 

        <label for="preRemove-product-<?= $product->id;?>" class="action inline delete" title="Удалить товар"></label>

    </div>

    <div class="del-form hidden">
        <form action="<?= 'catalog'.($cid ? '/cat-'.$cid : '').'/delete-product-'.$product->id; ?>" name="catalog-form-<?= $product->id;?>" method="POST" class="quest">
            <input type="hidden" name="_method" value="DELETE" />
            <input type="submit" onclick="catalog.post(document.forms['catalog-form-<?= $product->id;?>']); return false;" value="" title="Удалить" class="inline yes icon" />
            <label for="preRemove-product-<?= $product->id;?>" title="Отмена" class="inline no icon"></label>
        </form>
    </div>

    <?php $picture = $product->getPicture('250x250'); ?>

    <label class="image" <?= $picture ? 'style="background-image:url('.$picture.');"' : '';?>></label>
    <div class="product-body">
        <div class="title"><?= $product->title; ?></div>
        <div class="descr"><?= $product->preview ?: 'Нет краткого описания товара.';?></div>
        <br />
        <div class="tags">Тэги: <?= implode(', ', $product->getTags()); ?> </div>
        <div class="price">Цена: <b><?= $product->price; ?> руб</b></div>
        <div class="date">
            <div class="icon inline"></div>
            <div class="d inline"><?= $product->getDate(); ?></div>
        </div>
    </div>

</div>
