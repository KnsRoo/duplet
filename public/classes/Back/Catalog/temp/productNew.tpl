<?php

use Websm\Framework\Router\Router;
$rDefault = Router::instance();
$rCreatProduct = Router::byName('Catalog.createProduct');

?>
<div class="product-edit">
    <div class="editing">
        <div class="edit-head relative">
            <div class="edit-head-title">Создание нового товара</div>
            <a href="<?= $rDefault->getBasePath(); ?>" onclick="catalog.moveTo(this.href); return false;" class="edit-head-closer absolute" title="Закрыть создание"></a>
        </div>
        <div class="edit-data box">
            <form action="<?= $rCreatProduct->getAbsolutePath(['cid' => $this->catalog]); ?>" name="catalog-form-new-product" method="POST">
                <div class="img-data inline relative">

                    <div class="img product-picture"></div>
                    <input type="checkbox" name="new-images-view" onchange="catalog.closeEmemy(this, 'image-search')" class="hidden min-images" id="image-explorer" />
                    <input type="radio" name="checked-mini-files" onchange="catalog.changeImage(this);" class="hidden" id="image-remove" />
                    <div class="actions">
                        <label for="image-explorer" class="action inline add" title="Выбрать новое изображение"></label>
                        <label for="image-search" class="action inline search" title="Подобрать / найти новое изображение"></label>
                    </div>
                    <div class="popup absolute files-min-manager hidden">
                        <div class="tit relative">
                            Файлы
                            <label for="image-explorer" class="close absolute" title="Закрыть"></label>

                        </div>

                        <input id="picture-for-product"
                            type="hidden"
                            value=""
                            name="create[picture]" />

                        <div class="cont box filesMin" 
                            data-types="jpg,bmp,png,gif,jpeg" 
                            data-checkable="true" 
                            data-set-value="#picture-for-product"
                            data-onchange="catalog.getProductPreview(this, '150x150');">
                        </div>

                    </div>
                    <div class="popup absolute search-images hidden">
                        <div class="tit relative">Подбор изображений<label for="image-search" class="close absolute" title="Закрыть"></label></div>
                        <div class="cont box"></div>
                    </div>

                </div>
                <div class="descr-data inline">
                    <div class="mini-title">Название</div>
                    <div class="mini-content">
                        <input type="text" class="sm-input" name="create[title]" value="" placeholder="Название товара" />
                    </div>
                    <div class="mini-title">Цена</div>
                    <div class="mini-content">
                        <input type="text" class="sm-input" name="create[price]" value="" placeholder="Цена товара" />
                    </div>
                    <div class="mini-title">Краткое описание</div>
                    <div class="mini-content">
                        <div class="text box">
                            <textarea name="create[preview]" class="sm-input"></textarea>
                        </div>
                    </div>
                    <div class="mini-title">Полное описание</div>
                    <div class="mini-content">
                        <div class="text box">
                            <textarea name="create[about]" class="ckeditor"></textarea>
                        </div>
                    </div>
                    <div class="btns">
                        <a href="<?= $rDefault->getBasePath();?>" onclick="catalog.moveTo(this.href); return false;" class="cancelBtn anim inline">Отмена</a>
                        <input type="submit" onclick="catalog.post(document.forms['catalog-form-new-product']); return false;" class="goodBtn anim inline" value="Сохранить" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
