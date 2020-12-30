<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Pages][creating]"
        value="on"
        type="checkbox"
        <?= $this->permitions['creating'] == 'off' ? '' : 'checked' ?>
        />
    <div class="icon"></div>
    Создание страниц
</label><br />
<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Pages][deleting]"
        value="on"
        type="checkbox"
        <?= $this->permitions['deleting'] == 'off' ? '' : 'checked' ?>
        />
    <div class="icon"></div>
    Удаление страниц
</label><br />
<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Pages][editing]"
        value="on"
        type="checkbox"
        <?= $this->permitions['editing'] == 'off' ? '' : 'checked' ?>
        />
    <div class="icon"></div>
    Редактирование страниц
</label><br />
<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Pages][presentation]"
        value="on"
        type="checkbox"
        <?= $this->permitions['presentation'] == 'off' ? '' : 'checked' ?>
        />
    <div class="icon"></div>
    Управление отображением страниц
</label><br />
<br />
<label>
    <b>id корневой страницы:</b><br />
    <input class="sm-input"
        type="text"
        name="permitions[Pages][chroot]"
        value="<?= $this->permitions['chroot']; ?>"
        maxlength="60" />
</label>
