<form action="<?= $rCreate->getAbsolutePath();?>" name="gallery-form-add-album" method="POST">
    <div class="img-data inline relative">
        <div class="img"></div>
    </div>
    <div class="descr-data inline">
        <div class="mini-title">Выберите фото</div>
        <div class="mini-content">
            <div class="actions">
                <div class="popup-files">
                    <div class="sm-options-frame to-right">
                        <input id="options-frame-trigger-to-left" class="options-frame-trigger" type="checkbox">
                        <label for="options-frame-trigger-to-left" class="opener"></label>
                        <input type="hidden" id="changed-album-photo" name="create[picture]" value="" />
                        <div class="frame" style="width: 330px;">
                            <div class="head" style="width: 500px;">
                                <label for="options-frame-trigger-to-left" class="closer"></label>
                                <span style="display: flex;justify-content: center;align-items: center;padding: 10px;">
                                    Файлы
                                </span>
                            </div>
                            <div class="content" style="width: 500px;height: 270px;">
                                <div class="filesMin"
                                    data-set-value="#changed-album-photo"
                                    data-types="jpeg,jpg,bmp,png,gif,mp4,webm"
                                    data-checkable="true"
                                    data-optional="true"
                                    data-onchange="gallery.getPreview(this, '300x300');">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mini-title">Название альбома</div>
        <div class="mini-content">
            <input type="text" class="sm-input" name="create[title]" placeholder="Название альбома" />
        </div>
        <div class="mini-title">Краткое описание альбома</div>
        <div class="mini-content">
            <div class="text">
                <textarea name="create[preview]" class="sm-input" cols="30" rows="5" class="preview"></textarea>
            </div>
        </div>
        <div class="mini-title">Список альбомов</div>
        <div class="mini-content">
            <select name="create[cid]" class="sm-input mini">

                <option value=""> Не в альбоме </option>

                <?php foreach ($albums as $album): ?>

                    <option value="<?= $album['id']; ?>">
                        <?= $album['title']; ?>
                    </option>

                <?php endforeach; ?>

            </select>
        </div>
        <div class="btns">
            <a href="<?= $rDefault->getBasePath(); ?>" onclick="gallery.moveTo(this.href); return false;" class="sm-button cancel">Отмена</a>
            <input type="submit" onclick="gallery.post(document.forms['gallery-form-add-album']); return false;" class="sm-button" value="Сохранить" />
        </div>
    </div>
</form>
