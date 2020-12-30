<div class="head-line relative">
    <div class="path inline">
        <a class="path-link"
           href="<?= $this->url;?>"
           onclick="users.moveTo(this.href); return false;">
           Корень
        </a>
    </div>
    <div class="main-title inline"><?= $this->title;?></div>
</div>
<div class="data-content">
    <div class="module-data">
        <div id='users-app' 
            data-api-link='/admin/siteusers/api/'
            data-users-link='/admin/siteusers/api/users'>
        </div>
    </div>
</div>
<div class="sm-accordion sm-frame group fixed">
    <input class="state-trigger" id="accordion-state-trigger" type="checkbox">
    <label class="toggler title head" for="accordion-state-trigger">Дополнительные действия</label>
    <div class="content">
        <form action="/admin/siteusers/mailing" method="POST">
            <div class="additional-actions">
                <input required type="text" name="title" placeholder="Заголовок рассылки" class="sm-input additional-actions__input">
                <textarea required type="text" name="text" placeholder="Текст рассылки" class="sm-input additional-actions__textarea ckeditor"></textarea>
                <input class="sm-button save" type="submit" value="Отправить рассылку">
            </div>
        </form>
    </div>
</div>
