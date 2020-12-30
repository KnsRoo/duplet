<div class="repeat">
	<?php if ($this->permitions['uploading-files'] == 'on'): ?>
	<form action="<?= $this->url.($this->folder->id ? '/folder-'.$this->folder->id : '').'/create-file'; ?>" name="files-create-file-form-<?= $id;?>" method="POST" class="add-new inline" enctype="multipart/form-data">
		<input type="file" id="file-uploader-<?= $id;?>" name="files[]" multiple required onchange="myfiles.changeLabel(this);" class="hidden" />
		<label for="file-uploader-<?= $id;?>" class="new-file inline">Выбрать файлы</label>
		<input type="submit" onclick="myfiles.post(document.forms['files-create-file-form-<?= $id;?>']); return false;" class="goodBtn inline" value="Загрузить" name="add-new-file" />
	</form>
	<?php endif; ?>
	<?= $this->pager; ?>
</div>
