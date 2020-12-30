<?php
use Websm\Framework\Router\Router;

$rUpdateCatId = Router::byName('Catalog.updateCatId');

?>
<div class="cat-edit">
    <div class="editing">

        <div class="edit-head relative">
            <div class="edit-head-title">Редактирование категории "<?= $catalog->title; ?>"</div>
            <a href="catalog/cat-<?= $catalog->id; ?>"
                onclick="catalog.moveTo(this.href); return false;"
                class="edit-head-closer absolute"
                title="Закрыть редактирование"></a>
        </div>

        <div class="edit-data box">
            <form action="<?= $rUpdateCatId->getAbsolutePath(['cid' => $catalog->id, 'id' => $catalog->id]); ?>"
                name="catalog-form-<?= $catalog->id; ?>"
                method="POST">

                <input type="hidden" name="_method" value="PUT" />

                <div class="img-data inline relative">

                    <?php 
                    $picture = $catalog->getPicture('150x150');
                    $style = $picture ? 'background-image: url('.$picture.');' : '';
                    ?>

                    <div class="img cat-picture" style="<?= $style; ?>"></div>
                    <input type="checkbox" name="new-images-view" onchange="catalog.closeEmemy(this, 'image-search')" class="hidden min-images" id="image-explorer" />
                    <input type="checkbox" name="new-images-view" onchange="catalog.closeEmemy(this, 'image-explorer')" class="hidden search-images" id="image-search" />
                    <input type="radio" name="checked-mini-files" onchange="catalog.changeImage(this);" class="hidden" id="image-remove" />
                    <div class="actions">
                        <label for="image-explorer" class="action inline add" title="Выбрать новое изображение"></label>
                        <label for="image-remove" class="action inline remove" title="Удалить текущее изображение"></label>
                    </div>
                    <div class="popup absolute files-min-manager hidden">
                        <div class="tit relative">
                            Файлы
                            <label for="image-explorer" class="close absolute" title="Закрыть"></label>
                        </div>

                        <input id="picture-for-<?= $catalog->id; ?>"
                            type="hidden"
                            value="<?= $catalog->picture; ?>"
                            name="update[picture]" />

                        <div class="cont box filesMin"
                            data-optional="true"
                            data-types="jpg,bmp,png,gif,svg"
                            data-checkable="true"
                            data-set-value="#picture-for-<?= $catalog->id; ?>"
                            data-onchange="catalog.getCatPreview(this, '150x150');"></div>
                    </div>
                </div>
                <div class="descr-data inline">
                    <div class="mini-title">Название</div>
                    <div class="mini-content">
                        <input type="text"
                        class="text-text"
                        name="update[title]"
                        value="<?= htmlspecialchars($catalog->title); ?>"
                        placeholder="Название категории" />
                    </div>

                    <div class="mini-title">Мета тег TITLE</div>
                    <div class="mini-content">
                        <input type="text"
                        class="text-text"
                        name="update[meta_title]"
                        value="<?= htmlspecialchars($catalog->meta_title); ?>"
                        placeholder="Мета тег TITLE" />
                    </div>

                    <div class="mini-title">Ключевые слова</div>
                    <div class="mini-content">
                        <textarea class="sm-input"
                            style="width: 99%;"
                            name="update[keywords]"
                            placeholder="Ключевые слова страницы (запись через запятую)"><?= $catalog->keywords; ?></textarea>
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
                        $tags = $catalog->getTags();
                        foreach ($catalog->getAllTags() as $tag): 
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
                            <textarea name="update[preview]" class="preview"><?= $catalog->preview; ?></textarea>
                        </div>
                    </div>
                    <div class="mini-title">Полное описание</div>
                    <div class="mini-content">
                        <div class="text box">
                            <textarea name="update[about]" class="ckeditor"><?= $catalog->about; ?></textarea>
                        </div>
                    </div>

                    <div class="mini-title">Краткое описание</div>
                    <div class="mini-content">
                        <div class="text box">
                            <textarea name="update[preview]" class="preview"><?= $catalog->preview; ?></textarea>
                        </div>
                    </div>
                    <div class="mini-title">Полное описание</div>
                    <div class="mini-content">
                        <div class="text box">
                            <textarea name="update[about]" class="ckeditor"><?= $catalog->about; ?></textarea>
                        </div>
                    </div>
                    <div class="btns">

                        <a href="catalog/cat-<?= $catalog->id; ?>"
                            onclick="catalog.moveTo(this.href); return false;"
                            class="cancelBtn anim inline">Отмена</a>

                        <input type="submit" 
                            onclick="catalog.post(document.forms['catalog-form-<?= $catalog->id; ?>']); return false;"
                            class="goodBtn anim inline"
                            value="Сохранить" />

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
