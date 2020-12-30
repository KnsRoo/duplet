<input type="checkbox" class="hidden preRename" id="preRename-folder-<?= $id;?>" onchange="layout.sel('rename-input-folder-<?= $id;?>');" />
<input type="checkbox" class="hidden preRemove" id="preRemove-folder-<?= $id;?>" />
<li class="item relative" data-id="<?= $id;?>" data-class="drag-drop-place">
	<a href="<?= $this->url.'/folder-'.$id; ?>" onclick="myfiles.moveTo(this.href); return false;" class="link-with-icons inline"><?= $title;?></a>
	<div class="actions inline">
		<label for="preRename-folder-<?= $id;?>" class="action inline edit" title="Редактировать название папки"></label>
		<label for="preRemove-folder-<?= $id;?>" class="action inline delete" title="Удалить папку"></label>
	</div>
	<div class="del-form hidden inline absolute">
		<div class="v-align"></div>
		<form action="<?= $this->url.($this->folder ? '/folder-'.$this->folder : '').'/delete-folder-'.$id; ?>" name="files-delete-folder-form-<?= $id;?>" method="POST" class="quest inline">
			<input type="hidden" name="_method" value="DELETE" />
			<input type="submit" onclick="myfiles.post(document.forms['files-delete-folder-form-<?= $id;?>']); return false;" title="Удалить" class="inline yes icon" />
			<label for="preRemove-folder-<?= $id;?>" title="Отмена" class="inline no icon"></label>
		</form>
	</div>
	<div class="rename-form hidden inline absolute">
		<div class="v-align"></div>
		<form action="<?= $this->url.($this->folder ? '/folder-'.$this->folder : '').'/update-title-folder-'.$id; ?>" name="files-update-folder-form-<?= $id;?>" method="POST" class="quest inline">
			<input type="hidden" name="_method" value="PUT" />
			<input type="text" class="rename-input inline" name="update-title" value="<?= $title;?>" id="rename-input-folder-<?= $id;?>" placeholder="Название папки" />
			<input type="submit" onclick="myfiles.post(document.forms['files-update-folder-form-<?= $id;?>']); return false;" title="Сохранить" class="inline yes icon" />
			<label for="preRename-folder-<?= $id;?>" title="Отмена" class="inline no icon"></label>
		</form>
	</div>
</li>
