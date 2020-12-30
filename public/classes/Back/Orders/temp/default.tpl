<div class="head-line relative">
    <div class="path inline">
        <a class="path-link"
            href="<?= $this->url;?>"
            onclick="orders.moveTo(this.href); return false;">
            Корень
        </a>
    </div>
    <div class="main-title inline"><?= $this->title;?></div>
</div>
<div id='orders-app' data-links-orders='/admin/orders/api/orders?embed=props'></div>
<div class="sm-accordion sm-frame group fixed">
    <input class="state-trigger" id="accordion-state-trigger" type="checkbox">
    <label class="toggler title head" for="accordion-state-trigger">Дополнительные действия</label>
    <div class="content">
        <form action="<?= $exportAction; ?>" method="GET">
            <select name="month" required class="sm-input">
                <option value="" selected>Выберите месяц</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
            <input required type="number" min="1000" max="9999" name="year" placeholder="Введите год в формате YYYY" class="sm-input">
            <input class="sm-button save" type="submit" value="Выгрузить заказы">
        </form>
    </div>
    <div class="content">
        <form action="/admin/orders/mailing" method="POST">
            <div class="additional-actions">
                <input required type="text" name="title" placeholder="Заголовок рассылки" class="sm-input additional-actions__input">
                <textarea required type="text" name="text" placeholder="Текст рассылки" class="sm-input additional-actions__textarea ckeditor"></textarea>
                <input class="sm-button save" type="submit" value="Отправить рассылку">
            </div>
        </form>
    </div>
</div>
