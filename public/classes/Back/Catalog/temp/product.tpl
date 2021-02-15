<?php

use Websm\Framework\Router\Router;
use Core\Misc\MiniPager\MiniPager;

$rUpdateProduct = Router::byName('Catalog.updateProduct');
$extraProps = json_decode($product->props, true);
$count = isset($extraProps['Количество']) ? $extraProps['Количество'] : null;

?>
<input type="checkbox" class="hidden preRemove" id="preRemove-product-<?= $product->id;?>" />
<div data-subscribe="cat" data-product="true" data-message='{"id": "<?= $product->id; ?>", "type": "product"}' class="product relative box <?= $product->visible ? '' : 'unvis';?> <?= ($product->id == $this->updateProduct) ? 'edit' : '';?>" data-link="object-<?= $product->id;?>">
    <div class="actions">
        <div class="hand-mover inline" title="Схватить и переместить товар" data-class="drag-item" data-id="<?= $product->id;?>" data-link="<?= $product->id;?>"></div>

        <form name="catalog-update-sort-product-form-<?= $product->id;?>" action="<?= $this->url.($this->cat ? '/cat-'.$this->cat : '').'/update-sort-product-'.$product->id; ?>" method="POST" class="inline"
            onsubmit="catalog.post(document.forms['catalog-update-sort-product-form-<?= $product->id;?>']); return false;">
            <input type="hidden" name="_method" value="PUT" />
            <input type="text" class="num" name="sort" value="<?= (int)$product->sort; ?>" title="Порядковый номер товара" />
        </form>

        <?php 
        $href = $rUpdateProduct->getAbsolutePath([
            'cid' => $this->catalog,
            'id' => $product->id
        ]);
        ?>

        <a href="<?= $href; ?>" onclick="catalog.getTab();" class="action inline edit" title="Редактировать товар"></a> 
        <form action="<?= $this->url.($this->cat ? '/cat-'.$this->cat : '').'/update-visibility-product-'.$product->id; ?>" name="catalog-update-visibility-product-form-<?= $product->id;?>" method="POST" class="inline action">
            <input type="hidden" name="_method" value="PUT" />
            <input type="submit" onclick="catalog.post(document.forms['catalog-update-visibility-product-form-<?= $product->id;?>']); return false;" class="action inline visible" title="Видимость товара" />
        </form>

        <label for="preRemove-product-<?= $product->id;?>" class="action inline delete" title="Удалить товар"></label>

        <input type="checkbox" name="checked[]" onchange="catalog.addToDragCont(this)" id="mark-<?= $product->id;?>" value="<?= $product->id;?>" class="hidden checker" />
        <label for="mark-<?= $product->id;?>" class="action inline check" title="Отметить"></label>
    </div>
    <div class="del-form hidden">
        <form action="<?= $this->url.($this->cat ? '/cat-'.$this->cat : '').'/delete-product-'.$product->id; ?>" name="catalog-form-<?= $product->id;?>" method="POST" class="quest">
            <input type="hidden" name="_method" value="DELETE" />
            <input type="submit" onclick="catalog.post(document.forms['catalog-form-<?= $product->id;?>']); return false;" value="" title="Удалить" class="inline yes icon" />
            <label for="preRemove-product-<?= $product->id;?>" title="Отмена" class="inline no icon"></label>
        </form>
    </div>
    <!-- <div class="search"> -->
    <!--     <form action="https://www.google.ru/search" method="GET"> -->

    <!--         <input type="hidden" name="q" value="<?= $product->title; ?>" /> -->
    <!--         <input type="hidden" name="site" value="imghp" /> -->
    <!--         <input type="hidden" name="tbm" value="isch" /> -->
    <!--         <input type="submit" class="button-ya" value="Поиск в Google" style="margin: 10px; margin-left: 70px;" /> -->

    <!--     </form> -->
    <!--     <form action="https://yandex.ru/images/search" method="GET"> -->

    <!--         <input type="hidden" name="text" value="<?= $product->title; ?>" /> -->
    <!--         <input type="submit" class="button-ya" value="Поиск в Yandex" style="margin: 10px; margin-left: 70px;" /> -->

    <!--     </form> -->
    <!-- </div> -->

    <input type="radio" name="file-manager-toggler" id="file-manager-toggler-null-<?= $product->id;?>" class="hidden" />
    <input type="radio" name="file-manager-toggler" id="file-manager-toggler-<?= $product->id;?>" class="hidden fileToggler" />
    <div class="popup absolute files-min-manager hidden">
        <div class="tit relative">Файлы<label for="file-manager-toggler-null-<?= $product->id;?>" class="close absolute" title="Закрыть"></label></div>
        <div id="file-manager-<?= $product->id; ?>"
            class="cont filesMin"
            autoload="false"
            data-optional="true"
            data-types="jpeg,jpg,bmp,png,gif"
            data-checkable="true"
            data-onchange="catalog.updatePictureForProduct(this, '<?= $product->id; ?>');"></div>

    </div>

    <?php $picture = $product->getPicture('500x500'); ?>

    <div class="search">
        <form action="https://www.google.ru/search" method="GET">

            <input type="hidden" name="q" value="<?= $product->title; ?>" />
            <input type="hidden" name="site" value="imghp" />
            <input type="hidden" name="tbm" value="isch" />
            <input type="submit" class="button-ya" value="Поиск в Google" style="margin: 10px; margin-left: 70px;" />

        </form>
        <form action="https://yandex.ru/images/search" method="GET">

            <input type="hidden" name="text" value="<?= $product->title; ?>" />
            <input type="submit" class="button-ya" value="Поиск в Yandex" style="margin: 10px; margin-left: 70px;" />

        </form>
    </div>

    <label for="file-manager-toggler-<?= $product->id;?>" onclick="FilesMin.load(sf('#file-manager-<?= $product->id; ?>')[0]);" class="image" <?= $picture ? 'style="background-image:url('.$picture.');"' : '';?>></label>
    <div class="product-body">
        <div class="title"><?= $product->title; ?></div>
        <div class="descr"><?= $product->preview ?: 'Нет краткого описания товара.';?></div>
        <br />
        <div class="tags">Тэги: <?= implode(', ', $product->getTags()); ?> </div>
        <div class="price">Цена: <b><?= $product->price; ?> руб</b></div>
        <?php if($count): ?>
        <div class="eav-count">Дополнителые свойства: <b><?= $count['value']; ?> <?= $count['unit']; ?></b></div>
        <?php endif; ?>
        <div class="date">
            <div class="icon inline"></div>
            <div class="d inline"><?= $product->getDate(); ?></div>
        </div>
    </div>

</div>
