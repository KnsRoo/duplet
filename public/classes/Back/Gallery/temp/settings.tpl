<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Gallery][creating-album]"
		value="on"
		type="checkbox"
		<?= $this->permitions['creating-album'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Создание альбомов
</label><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Gallery][creating-photo]"
		value="on"
		type="checkbox"
		<?= $this->permitions['creating-photo'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Создание фотографий
</label><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Gallery][deleting-album]"
		value="on"
		type="checkbox"
		<?= $this->permitions['deleting-album'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Удаление альбомов
</label><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Gallery][deleting-photo]"
		value="on"
		type="checkbox"
		<?= $this->permitions['deleting-photo'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Удаление фотографий
</label><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Gallery][editing-album]"
		value="on"
		type="checkbox"
		<?= $this->permitions['editing-album'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Редактирование альбомов
</label><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[Gallery][editing-photo]"
		value="on"
		type="checkbox"
		<?= $this->permitions['editing-photo'] == 'off' ? '' : 'checked' ?>
		/>
	<div class="icon"></div>
	Редактирование фото
</label><br />
<br />
<label>
	<b>id корневого альбома:</b><br />
	<input class="sm-input"
		type="text"
		name="permitions[Gallery][chroot]"
		value="<?= $this->permitions['chroot']; ?>"
		maxlength="60" />
</label>
