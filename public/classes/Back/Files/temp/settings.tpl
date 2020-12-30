<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Files][creating-folders]"
		value="on"
		type="checkbox"
		<?= $this->permitions['creating-folders'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Создание папок
</label><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Files][uploading-files]"
		value="on"
		type="checkbox"
		<?= $this->permitions['uploading-files'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Загрузка файлов
</label><br />

<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Files][deleting-folders]" 
		value="on"
		type="checkbox"
		<?= $this->permitions['deleting-folders'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Удаление папок
</label><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Files][deleting-files]" 
		value="on"
		type="checkbox"
		<?= $this->permitions['deleting-files'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Удаление файлов
</label><br />

<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Files][editing-folders]"
		value="on"
		type="checkbox"
		<?= $this->permitions['editing-folders'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Редактирование папок
</label><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Files][editing-files]"
		value="on"
		type="checkbox"
		<?= $this->permitions['editing-files'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Редактирование файлов
</label><br />

<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Files][self-only]"
		value="on"
		type="checkbox"
		<?= $this->permitions['self-only'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Только свои файлы и папки
</label><br />

<br />
<label>
	<b>id корневой папки:</b><br />
	<input class="sm-input"
		type="text"
		name="permitions[Files][chroot]"
		value="<?= $this->permitions['chroot']; ?>"
		maxlength="60" />
</label>
