<?php

use Model\FileMan\File;
use Back\Files\Config;
$origin = Websm\Framework\Router\Router::getOrigin();
?>
<div id="right-line-place-<?= $page->id; ?>">
    <div class="edit-line">
        <div class="editing">
            <div class="edit-head relative">
                <div class="edit-head-title">Редактирование страницы "<?= $page->title; ?>"</div>
                <a href="<?= $this->url . ($this->page ? '/page-' . $this->page : ''); ?>/?page-number=<?= $this->pageNumber; ?>"
                onclick="pages.moveTo(this.href); return false;" class="edit-head-closer absolute" title="Закрыть редактирование"></a>
            </div>

            <div class="open-tab-button">

                <button class="tab sm-button no-radius"
                    data-tab-frame="#page"
                    data-tab-selected="true">Страница</button>

                <button class="tab sm-button no-radius" data-tab-frame="#eav">Дополнительные свойства</button>

            </div>

            <div class="edit-data box tab-frame" id="page">
                <form action="<?= $this->url . ($this->page ? '/page-' . $this->page : '') . '/update-page-' . $page->id; ?>" method="POST" name="pages-update-form-<?= $page->id; ?>">
                    <div class="wrapper-edit-box">
                        <div class="left">
                            <?php

                            $file = File::find(['id' => $page->picture])->get();

                            $picture = null;

                            if ($file->isPicture())
                                $picture = $file->getPicture('150x150');

                            elseif ($file->isVideo())
                                $picture = $file->getVideoPreview('150x150');

                            ?>
                            <div class="picture" <?php if ($picture) : ?> style="background-image: url(<?= Config::PREFIX_PATH . '/' . $picture; ?>);" <?php endif; ?> title="Основное изображение раздела"></div>

                            <div class="actions">
                                <label class="add button" for="action-open-files" title="Установить изображение"></label>
                                <label class="clear button" title="Сбросить изображение" onclick="pages.clearPicture('<?= $page->id; ?>'); pages.changeImage(this);"></label>
                                <input type="checkbox" id="action-open-files" class="open-files" />
                                <div class="popup-files">
                                    <div class="header relative">
                                        Файлы
                                        <label class="closer" for="action-open-files">x</label>
                                    </div>

                                    <input id="picture-for-<?= $page->id; ?>" type="hidden" name="update[picture]" value="<?= !$file->isNew() ? $file->id : ''; ?>" />

                                    <?php if (!$file->isNew()) : ?>

                                        <input class="hidden" type="radio" name="file" value="<?= $file->id; ?>" checked />
                                    <?php endif; ?>

                                    <div class="filesMin" data-types="jpeg,jpg,bmp,png,gif,mp4,webm" data-checkable="true" data-optional="true" data-set-value="#picture-for-<?= $page->id; ?>" data-onchange="pages.getPreview(this, '150x150');"></div>
                                </div>
                            </div>

                            <div class="icon-and-actions">

                                <?php $icon = $page->getIcon(); ?>

                                <div class="sm-picture <?= $icon ? 'contain' : ''; ?>" <?php if ($icon) : ?> style="background-image: url(<?= $icon; ?>);" <?php endif; ?> title="Иконка раздела">
                                </div>

                                <input id="icon-for-<?= $page->id; ?>" type="hidden" name="update[icon]" value="<?= $page->icon; ?>" />

                                <div class="sm-dialog to-right">

                                    <input id="dialog-trigger-to-right" class="dialog-trigger" type="checkbox">

                                    <div class="content">
                                        <label for="dialog-trigger-to-right" class="sm-action-button set-image" title="Установить иконку"></label>
                                    </div>

                                    <div class="frame">
                                        <div class="head">
                                            Файлы
                                            <label for="dialog-trigger-to-right" class="closer">x</label>
                                        </div>
                                        <div class="content" style="padding: 0px; height: 350px;">

                                            <div class="filesMin" data-types="svg" data-checkable="true" data-optional="true" data-set-value="#icon-for-<?= $page->id; ?>"></div>

                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="right">
                            <input type="hidden" name="_method" value="PUT" />
                            <div class="mini-title">Название</div>
                            <div class="mini-content">
                                <input type="text" class="sm-input" name="update[title]" value="<?= $page->title; ?>" style="width: 50%" placeholder="Название страницы" />
                            </div>

                            <div class="mini-title">Ссылка на страницу</div>
                            <div class="mini-content">

                                <div class="sm-input-lock" style="width: 50%;">
                                    <input type="text" class="sm-input" name="update[chpu]" value='<?= $page->chpu; ?>' style="width: 100%" placeholder="Ссылка на страницу" disabled />
                                    <button title="Разблокировать" class="toggler unlock" onclick="SMInputLockToggler.toggle(this.parentNode);return false;"></button>
                                </div>

                            </div>

                            <label class="mini-title" for="core_page_checkbox">Сделать страницу корневой</label>     
                            <input id="core_page_checkbox" class="sm-input" name="core-page" type="checkbox"  <?= ($page->core_page)? 'checked' : ''; ?>  style="width: 0px; margin-bottom: 15px; "/>

                            <div class="mini-title">Тэги</div>
                            <div class="mini-content tags">

                                <input class="sm-input" name="new-tags" value="" placeholder="Дополнить тэги: тэг1, тэг2 ..." type="text" />

                                <br /> <br />

                                <div class="scroll-content">

                                    <?php

                                    $tags = $page->getTags();
                                    foreach ($page->getAllTags() as $tag) :
                                    ?>

                                        <div class="sm-tag tag-<?= $tag->id; ?> <?= $tag->static ? 'static' : ''; ?>">
                                            <label class="tag-toggler">
                                                <input class="tag-trigger" type="checkbox" name="update[tags][]" value="<?= $tag->title; ?>" <?= in_array($tag->title, $tags) ? 'checked' : ''; ?> />

                                                <span class="title"> <?= $tag->title; ?> </span>
                                            </label>
                                            <a href="#" class="rm-button" title="Удалить" onclick="pages.deleteTag('<?= $tag->id; ?>'); return false;">+</a>
                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                            <div class="mini-title">Дата создания</div>
                            <div class="mini-content">
                                <input type="text" class="sm-input correct" name="update[date]" value="<?= $page->getDate(); ?>" placeholder="Дата создания страницы" style="width: 50%" data-pattern="XX.XX.XXXX" />
                            </div>

                            <div class="mini-title">Ключевые слова</div>
                            <div class="mini-content">
                                <div class="announce-wrapper">
                                    <textarea class="sm-input" name="update[keywords]" placeholder="Ключевые слова страницы (запись через запятую)"><?= $page->keywords; ?></textarea>
                                </div>
                            </div>

                            <div class="mini-title">Текст анонса</div>
                            <div class="mini-content">
                                <div class="announce-wrapper">
                                    <textarea class="sm-input limit-chars" data-count-output=".announce-wrapper > .count" name="update[announce]" rows="2" maxlength="300" placeholder="Текст для анонса страницы"><?= $page->announce; ?></textarea>
                                    <div class="count">0</div>
                                </div>
                            </div>

                            <div class="mini-title">Описание</div>
                            <div class="mini-content">
                                <div class="text inline box" style="margin: 5px;">
                                    <textarea name="update[text]" id="update-<?= $page->id; ?>" class="ckeditor" style="width: 100%;"><?= $page->text; ?></textarea>
                                </div>
                                <div class="files inline">
                                    <span class="mini-title">Файловый менеджер</span>
                                    <div class="this-files filesMin no-padding" data-draggable="true"></div>
                                </div>
                            </div>
                            <div class="btns">
                                <a href="<?= $this->url . ($this->page ? '/page-' . $this->page : ''); ?>/?page-number=<?= $this->pageNumber; ?>" onclick="pages.moveTo(this.href); return false;" class="cancelBtn anim inline">Отмена</a>
                                <input type="submit" onclick="pages.post(document.forms['pages-update-form-<?= $page->id; ?>']); return false;" class="goodBtn anim inline" value="Сохранить" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="tab-frame" id="eav">
                <div class="add-eav-property edit-data box">
                    <!-- Vue app here -->
                    <div id='props-app'
                        data-api-link-props='<?= $origin; ?>/admin/pages/api/pages/<?= $page->id; ?>/props'
                        data-api-link-tags="<?= $origin; ?>/admin/pages/api/tags"
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
</div>
