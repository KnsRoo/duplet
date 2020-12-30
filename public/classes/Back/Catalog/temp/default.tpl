<?php

use \Back\Files\File;
use Websm\Framework\Router\Router;

$rCreatCat = Router::byName('Catalog.createCat');

?>
<div class="head-line relative">
    <div class="path inline">
        <a class="path-link" href="<?= $this->url;?>" onclick="catalog.moveTo(this.href); return false;">Корень</a>
        <?=$this->newPath?>
    </div>
    <div class="main-title inline"><?= $this->title; ?></div>
</div>
<div class="data-content">
    <nav class="module-menu inline">
        <ul class="ul">
            <?php

                if ($this->catalog) {
                    echo '
                        <li data-channel-name="cat" class="item back" data-id="'.($this->parent ? : '0').'" data-class="drag-drop-place">
                            <a href="'.$this->url.($this->parent ? '/cat-'.$this->parent : '').'" onclick="catalog.moveTo(this.href); return false;" class="link">Назад</a>
                        </li>
                    ';
                }

                foreach($catalogs as $catalog)
                    echo $this->render(__DIR__.'/cat.tpl', ['catalog' => $catalog]);

            ?>
        </ul>
        <form action="<?= $rCreatCat->getAbsolutePath(['cid' => $this->catalog]); ?>"
            name="catalog-form-new-cat"
            method="POST"
            class="add-new relative">

            <input type="text"
                value=""
                name="create[title]"
                placeholder="Название новой категории"
                class="text-row inline anim text-focus" />

            <input type="submit"
                onclick="catalog.post(document.forms['catalog-form-new-cat']); return false;"
                class="goodBtn inline"
                title="Создать" />

        </form>
    </nav>
    <div class="module-data inline">

        <?= $this->topForm; ?>

        <?= $this->render(__DIR__.'/repeat.tpl');?>

        <?= $this->render(__DIR__.'/popup.tpl');?>

        <!-- Товары -->
        <div class="data-place">

            <?= $this->render(__DIR__.'/product-cart.tpl'); ?>

            <?php
                foreach($products as $product)
                    echo $this->render(__DIR__.'/product.tpl', ['product' => $product]);
            ?>

        </div>
        <!-- /Товары -->

        <?= $this->render(__DIR__.'/repeat.tpl');?>

    </div>
</div>
