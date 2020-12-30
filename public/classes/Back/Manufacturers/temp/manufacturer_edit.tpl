<?php
    use \Model\FileMan\File;
    use \Back\Files\Config;
?>
<div id="right-line-place-<?= $manufacturer->id;?>">
    <div class="edit-line">
        <div class="editing">
            <div class="edit-head relative">
                <div class="edit-head-title">Редактирование производителя "<?= $manufacturer->name; ?>"</div>
                <a href="<?= $this->url;?>/?page=<?= $this->page; ?>" onclick="manufacturers.moveTo(this.href); return false;" class="edit-head-closer absolute" title="Закрыть редактирование"></a>
            </div>
            <div class="edit-data box">
                <form action="<?= $this->url.'/update-manufacturer-'.$manufacturer->id; ?>" method="POST" name="manufacturers-update-form-<?= $manufacturer->id;?>">
                    <div class="wrapper-edit-box">
                        <div class="left">
                            <?php

                                $file = File::find(['id' => $manufacturer->picture])->get();

                                $picture = null;

                                if ($file->isPicture())
                                    $picture = $file->getPicture('150x150');

                                elseif ($file->isVideo())
                                    $picture = $file->getVideoPreview('150x150');

                            ?>
                            <div class="picture"
                                <?php if ($picture): ?>
                                style="background-image: url(<?= Config::PREFIX_PATH.'/'.$picture; ?>);"
                                <?php endif; ?>
                                title="Изображение производителя" ></div>

                            <div class="actions">
                                <label class="add button" for="action-open-files" title="Установить изображение"></label>
                                <label class="clear button" title="Сбросить изображение" onclick="manufacturers.clearPicture('<?= $manufacturer->id; ?>'); manufacturers.changeImage(this);"></label>
                                <input type="checkbox" id="action-open-files" class="open-files" />
                                <div class="popup-files">
                                    <div class="header relative">
                                        Файлы
                                        <label class="closer" for="action-open-files">x</label>
                                    </div>

                                    <input id="picture-for-<?= $manufacturer->id; ?>"
                                        type="hidden"
                                        name="update[picture]"
                                        value="<?= !$file->isNew() ? $file->id : ''; ?>" />

                                    <?php if (!$file->isNew()): ?>

                                    <input class="hidden"
                                        type="radio"
                                        name="file"
                                        value="<?= $file->id; ?>"
                                        checked />
                                    <?php endif; ?>

                                    <div class="filesMin"
                                        data-types="jpeg,jpg,bmp,png,gif,mp4,webm"
                                        data-checkable="true"
                                        data-optional="true"
                                        data-set-value="#picture-for-<?= $manufacturer->id; ?>"
                                        data-onchange="manufacturers.getPreview(this, '150x150');"></div>
                                </div>
                            </div>
    
                        </div>
                        <div class="right">
                            <input type="hidden" name="_method" value="PUT" />
                            <div class="mini-title">Название</div>
                            <div class="mini-content">
                                <input type="text"
                                    class="sm-input"
                                    name="update[name]"
                                    value="<?= $manufacturer->name;?>"
                                    style="width: 50%"
                                    placeholder="Название производителя" />
                            </div>

                            <div class="mini-title">Ссылка на сайт</div>
                            <div class="mini-content">
                                <input type="text"
                                    class="sm-input"
                                    name="update[url]"
                                    value="<?= $manufacturer->url;?>"
                                    style="width: 50%"
                                    placeholder="http://site.ru" />
                            </div>

                            <div class="btns">
                                <a href="<?= $this->url;?>/?page=<?= $this->page; ?>" onclick="manufacturers.moveTo(this.href); return false;" class="cancelBtn anim inline">Отмена</a>
                                <input type="submit" onclick="manufacturers.post(document.forms['manufacturers-update-form-<?= $manufacturer->id;?>']); return false;" class="goodBtn anim inline" value="Сохранить" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
