<?php

use \Back\Files\File;
use Websm\Framework\Router\Router;

$rNewProduct = Router::byName('Catalog.newProduct');

?>
<div class="product box new">
    <div class="actions"></div>
    <a href="<?= $rNewProduct->getAbsolutePath(['cid' => $this->catalog]); ?>"
        onclick="catalog.moveTo(this.href); return false;" class="image">
        <div class="goodBtn">
            <div class="goodBtn in"></div>
        </div>
        <div class="tit anim">Добавить новый товар</div>
    </a>
    <div class="product-body fake"></div>
</div>
