<div class="sm-frame edit">

    <div class="head blue">
        Редактирование слайда
        <a class="closer" href="<?= $rDefault->getBasePath(); ?>">x</a>
    </div>

    <div class="content">
        <form action="<?= $rUpdateSlide->getAbsolutePath(['id' => $slide->id]); ?>" name="slides-form-<?= $slide->id; ?>" method="POST">
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
                                <input type="hidden" id="changed-slide-photo"
                                name="update[picture]"
                                value="<?= $slide->picture; ?>" />

                            <div class="frame" style="width: 330px;">
                                <div class="head" style="width: 500px;">
                                    <label for="options-frame-trigger-to-left" class="closer"></label>
                                    <span style="display: flex;justify-content: center;align-items: center;padding: 10px;">
                                        Файлы
                                    </span>
                                </div>
                                <div class="content" style="width: 500px;height: 270px;">
                                    <div class="filesMin"
                                        data-set-value="#changed-slide-photo"
                                        data-types="jpeg,jpg,bmp,png,gif,mp4,webm"
                                        data-checkable="true"
                                        data-optional="true"
                                        data-onchange="slides.getPreview(this, '300x300');">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mini-title">Название слайда</div>
            <div class="mini-content">

                <input type="text"
                    class="sm-input"
                    name="update[title]"
                    value="<?= htmlspecialchars($slide->title); ?>"
                    placeholder="Название слайда" />

            </div>
            <div class="mini-title">Ссылка на страницу</div>
            <div class="mini-content">

                <input type="text"
                    class="sm-input"
                    name="update[link]"
                    value="<?= $slide->link; ?>"
                    placeholder="Ссылка на страницу" />

            </div>
            <div class="mini-title">Текст ссылки</div>
            <div class="mini-content">

                <input type="text"
                    class="sm-input"
                    name="update[link_text]"
                    value="<?= $slide->link_text; ?>"
                    placeholder="Текст ссылки" />

            </div>
            <div class="mini-title">Краткое описание слайда</div>
            <div class="mini-content">
                <div class="text">

                    <textarea name="update[preview]"
                        class="sm-input"
                        cols="30"
                        rows="5"
                        class="preview"><?= $slide->preview; ?></textarea>

                </div>
            </div>
            <div class="btns">
                <a href="<?= $rDefault->getBasePath(); ?>" onclick="slides.moveTo(this.href); return false;" class="sm-button cancel">Отмена</a>
                <input type="submit" onclick="slides.post(document.forms['slides-form-<?= $slide->id; ?>']); return false;" class="sm-button" value="Сохранить" />
            </div>
        </div>
    </form>
</div>
