<h1 class="title">Редактирование альбома - <?= $album->title; ?></h1>

<form action="<?= $rUpdatePhoto->getAbsolutePath(['id' => $album->id]); ?>" name="gallery-form-<?= $album->id; ?>" method="POST">
    <input name="_method" value="PUT" type="hidden">
    <div class="img-data inline relative">
        <div class="img" style="<?= $style; ?>"></div>
    </div>
    <div class="descr-data inline">
        <div class="mini-title">Выберите фото</div>
        <div class="mini-content">
            <div class="actions">
                <div class="popup-files">
                    <div class="sm-options-frame to-right">
                        <input id="options-frame-trigger-to-left" class="options-frame-trigger" type="checkbox">
                        <label for="options-frame-trigger-to-left" class="opener"></label>
                        <input type="hidden" id="changed-album-photo"
                            name="update[picture]"
                            value="<?= $album->picture; ?>" />

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
        <div class="mini-title">Название</div>
        <div class="mini-content">

            <input type="text"
                class="sm-input"
                name="update[title]"
                value="<?= htmlspecialchars($album->title); ?>"
                placeholder="Название изображения" />

        </div>
        <div class="mini-title">Краткое описание</div>
        <div class="mini-content">
            <div class="text">

                <textarea name="update[preview]"
                    class="sm-input"
                    cols="30"
                    rows="5"
                    class="preview"><?= $album->preview; ?></textarea>

            </div>
        </div>
        <div class="btns">
            <a href="<?= $rDefault->getBasePath(); ?>" onclick="gallery.moveTo(this.href); return false;" class="sm-button cancel">Отмена</a>
            <input type="submit" onclick="gallery.post(document.forms['gallery-form-<?= $album->id; ?>']); return false;" class="sm-button" value="Сохранить" />
        </div>
    </div>
</form>
