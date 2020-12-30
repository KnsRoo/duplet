<input type="checkbox" class="hidden preRename" id="preRename-folder-<?= $folder->id;?>" onchange="layout.sel('rename-input-folder-<?= $folder->id;?>');" />
<input type="checkbox" class="hidden preRemove" id="preRemove-folder-<?= $folder->id;?>" />
<li class="item relative" data-id="<?= $folder->id;?>" data-class="drag-drop-place">
	<a href="<?= $this->url.'/folder-'.$folder->id; ?>" onclick="myfiles.moveTo(this.href); return false;" class="link-with-icons inline"><?= $folder->title; ?></a>
	<div class="actions inline">
		<?php if ($this->permitions['editing-folders'] == 'on'): ?>
		<label for="preRename-folder-<?= $folder->id;?>" class="action inline edit" title="Редактировать название папки"></label>
		<?php endif; ?>
		<?php if ($this->permitions['deleting-folders'] == 'on'): ?>
		<label for="preRemove-folder-<?= $folder->id;?>" class="action inline delete" title="Удалить папку"></label>
		<?php endif; ?>
	</div>
	<div class="del-form hidden inline absolute">
		<div class="v-align"></div>
		<?php if ($this->permitions['deleting-folders'] == 'on'): ?>
		<form action="<?= $this->url.($this->folder->id ? '/folder-'.$this->folder->id : '').'/delete-folder-'.$folder->id; ?>" name="files-delete-folder-form-<?= $folder->id;?>" method="POST" class="quest inline">
			<input type="hidden" name="_method" value="DELETE" />
			<input type="submit" onclick="myfiles.post(document.forms['files-delete-folder-form-<?= $folder->id;?>']); return false;" title="Удалить" class="inline yes icon" />
			<label for="preRemove-folder-<?= $folder->id;?>" title="Отмена" class="inline no icon"></label>
		</form>
		<?php endif; ?>
	</div>
	<div class="rename-form hidden inline absolute">
		<div class="v-align"></div>
		<?php if ($this->permitions['editing-folders'] == 'on'): ?>
		<form action="<?= $this->url.($this->folder->id ? '/folder-'.$this->folder->id : '').'/update-title-folder-'.$folder->id; ?>" name="files-update-folder-form-<?= $folder->id;?>" method="POST" class="quest inline">
			<input type="hidden" name="_method" value="PUT" />
			<input type="text" class="rename-input inline" name="update-title" value="<?= $folder->title;?>" id="rename-input-folder-<?= $folder->id;?>" placeholder="Название папки" />
			<input type="submit" onclick="myfiles.post(document.forms['files-update-folder-form-<?= $folder->id;?>']); return false;" title="Сохранить" class="inline yes icon" />
			<label for="preRename-folder-<?= $folder->id;?>" title="Отмена" class="inline no icon"></label>
		</form>
		<?php endif; ?>
	</div>
</li>
