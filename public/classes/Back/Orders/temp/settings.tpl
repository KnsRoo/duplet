<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Orders][send-mail]"
        value="on"
        type="checkbox"
        <?= $this->permitions['send-mail'] === 'off' ? '' : 'checked' ?>
    />
    <div class="icon"></div>
    Отправлять e-mail о новом заказе
</label>
<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Orders][send-sms]"
        value="on"
        type="checkbox"
        <?= $this->permitions['send-sms'] === 'off' ? '' : 'checked' ?>
    />
    <div class="icon"></div>
    Отправлять sms о новом заказе
</label>