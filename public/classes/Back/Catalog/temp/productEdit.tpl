<?php 

use Websm\Framework\Router\Router;

$rUpdate = Router::byName('Catalog.updateProductId');
$origin = Router::getOrigin();

?>
<div class="product-edit">

    <input type='text' id='product-id' value='<?= $product->id; ?>' hidden>

    <div class="editing">
        <div class="edit-head relative">
            <div class="edit-head-title">Редактирование товара "<?= $product->title; ?>"</div>
            <a href="<?= $this->url.($product->cid ? '/cat-'.$product->cid : '');?>" onclick="catalog.moveTo(this.href); return false;" class="edit-head-closer absolute" title="Закрыть редактирование"></a>
        </div>

        <div class="open-tab-button">

            <button class="tab sm-button no-radius"
                data-tab-frame="#product"
                data-tab-selected="true">Товар</button>

            <button class="tab sm-button no-radius" data-tab-frame="#eav">Дополнительные свойства</button>

        </div>

        <div class="edit-data box tab-frame" id="product">

            <?php
            $action = $rUpdate->getAbsolutePath([
                'cid' => $this->catalog,
                'id' => $product->id
            ]);
            ?>
            <form action="<?= $this->url.($this->cat ? '/cat-'.$this->cat : '').'/update-visibility-product-'.$product->id; ?>" name="catalog-update-visibility-product-form-<?= $product->id;?>" method="POST" class="inline action">
            <input type="hidden" name="_method" value="PUT" />
            </form>
            <form action="<?= $action; ?>" name="catalog-form-<?= $product->id; ?>" method="POST">
                <input type="hidden" name="_method" value="PUT" />
                <div class="img-data inline relative">

                    <?php $picture = $product->getPicture('150x150'); ?>

                    <?= $picture; ?>

                    <div class="img product-picture" <?= $picture ? 'style="background-image:url('.$picture.');"' : ''; ?> ></div>
                    <input type="checkbox" name="new-images-view" onchange="catalog.closeEmemy(this, 'image-search');" class="hidden min-images" id="image-explorer" />
                    <input type="checkbox" name="new-images-view" onchange="catalog.closeEmemy(this, 'image-explorer');" class="hidden search-images" id="image-search" />
                    <input type="radio" name="checked-mini-files" onchange="catalog.changeImage(this);" class="hidden" id="image-remove" />

                    <div class="actions">
                        <label for="image-explorer" class="action inline add" title="Выбрать новое изображение"></label>
                        <!-- <label for="image-search" class="action inline search" title="Подобрать / найти новое изображение"></label> -->
                        <label for="image-remove" class="action inline remove" title="Удалить текущее изображение"></label>
                    </div>

                    <div class="popup absolute files-min-manager hidden">

                        <div class="tit relative">
                            Файлы
                            <label for="image-explorer" class="close absolute" title="Закрыть"></label>
                        </div>

                        <input id="picture-for-<?= $product->id; ?>"
                            type="hidden"
                            value="<?= $product->picture; ?>"
                            name="update[picture]" />

                        <div class="cont box filesMin"
                            data-optional="true"
                            data-types="jpeg,jpg,bmp,png,gif"
                            data-checkable="true"
                            data-set-value="#picture-for-<?= $product->id; ?>"
                            data-onchange="catalog.getProductPreview(this, '150x150');"></div>

                    </div>
                    <div class="popup absolute search-images hidden">
                        <div class="tit relative">Подбор изображений<label for="image-search" class="close absolute" title="Закрыть"></label></div>
                        <div class="cont box"></div>
                    </div>
                    <br />

                </div>
                <div class="descr-data inline">
                    <div class="mini-title">Название</div>
                    <div class="mini-content">
                        <input type="text" class="text-text" name="update[title]" value="<?= htmlspecialchars($product->title); ?>" placeholder="Название товара" />
                       <span class="<?= $product->visible == false ? 'unvis' : ''; ?>">
                        <span class="actions">
                        <span onclick="catalog.post(document.forms['catalog-update-visibility-product-form-<?= $product->id;?>']); return false;" class="action inline visible">Видимость товара</span>
                        </span>
                    </span>

                    </div>

                 
                    <div class="mini-title">Мета тег TITLE</div>
                    <div class="mini-content">
                        <input type="text"
                        class="text-text"
                        name="update[meta_title]"
                        value="<?= htmlspecialchars($product->meta_title); ?>"
                        placeholder="Мета тег TITLE" />
                    </div>

                    <div class="mini-title">Ключевые слова</div>
                    <div class="mini-content">
                        <textarea class="sm-input"
                            style="width: 99%;"
                            name="update[keywords]"
                            placeholder="Ключевые слова страницы (запись через запятую)"><?= $product->keywords; ?></textarea>
                    </div>

                    <div class="mini-title">Цена</div>
                    <div class="mini-content">
                        <input type="text" class="text-text" style="width:200px" name="update[price]" value="<?= $product->price; ?>" placeholder="Цена товара" />
                        <label>Скидка
                            <input type="text" class="text-text" style="width:60px" name="update[discount]" value="<?= $product->discount; ?>" placeholder="10" />
                       % 
                       </label>
                       <label>Текст 
                            <input type="text" class="text-text" style="width:300px" name="update[discount_text]" value="<?= $product->discount_text; ?>" placeholder="Текст на товаре" />
                       %
                       </label>
                    </div>
                    <div class="mini-title">Тэги</div>
                    <div class="mini-content tags">

                        <input class="sm-input"
                        name="new-tags"
                        value=""
                        placeholder="Дополнить тэги: тэг1, тэг2 ..."
                        type="text" />

                        <!-- <input class="sm-button add" value="добавить" type="button" /> -->

                        <br /> <br />

                        <?php
                        $tags = $product->getTags();
                        foreach ($product->getAllTags() as $tag): 
                        ?>

                        <div class="sm-tag tag-<?= $tag->id; ?> <?= $tag->static ? 'static' : ''; ?>">
                            <label class="tag-toggler"> 
                                <input class="tag-trigger"
                                    type="checkbox"
                                    name="update[tags][]"
                                    value="<?= $tag->title; ?>"
                                    <?= in_array($tag->title, $tags) ? 'checked' : ''; ?> />

                                <span class="title"> <?= $tag->title; ?> </span>
                            </label>
                            <a href="#" class="rm-button"
                                title="Удалить"
                                onclick="catalog.deleteTag('<?= $tag->id; ?>'); return false;">+</a>
                        </div>

                        <?php endforeach; ?>

                    </div>
                    <div class="mini-title">Краткое описание</div>
                    <div class="mini-content">
                        <div class="text box">
                            <textarea name="update[preview]" class="preview"><?= $product->preview; ?></textarea>
                        </div>
                    </div>
                    <div class="mini-title">Полное описание</div>
                    <div class="mini-content">
                        <div class="text box">
                            <textarea name="update[about]" class="ckeditor"><?= $product->about; ?></textarea>
                        </div>
                    </div>
                    <div class="btns">
                        <a href="<?= $this->url.($product->cid ? '/cat-'.$product->cid : '');?>" onclick="catalog.moveTo(this.href); return false;" class="cancelBtn anim inline">Отмена</a>
                        <input type="submit" onclick="catalog.post(document.forms['catalog-form-<?= $product->id; ?>']); return false;" class="goodBtn anim inline" value="Сохранить" />
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-frame" id="eav">
            <div class="add-eav-property edit-data box">
                <!-- Vue app here -->
                <div id='props-app'
                     data-api-link-props='<?= $origin; ?>/admin/catalog/api/products/<?= $product->id; ?>/props'
                     data-api-link-tags="<?= $origin; ?>/admin/catalog/api/tags"
                     data-api-link-products="<?= $origin; ?>/admin/catalog/api/products">
                </div>
            </div>
            <?php /*

                $data = [
                    'product' => $product,
                ];

                echo $this->render(__DIR__ . '/product-extra-property.tpl', $data);*/
            ?>
        </div>

    </div>
</div>
