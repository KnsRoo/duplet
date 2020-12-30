<b>Состояние:</b><br />
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[FilesMin][status]"
		value="on"
		<?php if ($this->permitions['status'] == 'on'): ?>
		checked
		<?php endif; ?>
		type="radio">
	<div class="icon"></div>
	Вкл
</label>
<label class="sm-toggler">
	<input class="toggle-trigger"
		name="permitions[FilesMin][status]"
		value="off"
		<?php if ($this->permitions['status'] == 'off'): ?>
		checked
		<?php endif; ?>
		type="radio">
	<div class="icon"></div>
	Выкл
</label>
