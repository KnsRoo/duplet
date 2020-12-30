<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Interview][creating]"
        value="on"
        type="checkbox"
        <?= $this->permitions['creating'] == 'off' ? '' : 'checked' ?>
        />
    <div class="icon"></div>
    Создание опросов
</label><br />
<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Interview][deleting]"
        value="on"
        type="checkbox"
        <?= $this->permitions['deleting'] == 'off' ? '' : 'checked' ?>
        />
    <div class="icon"></div>
    Удаление опросов
</label><br />
<label class="sm-toggler">
    <input class="toggle-trigger"
        name="permitions[Interview][editing]"
        value="on"
        type="checkbox"
        <?= $this->permitions['editing'] == 'off' ? '' : 'checked' ?>
        />
    <div class="icon"></div>
    Редактирование опросов
</label>
