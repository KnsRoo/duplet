<input type="checkbox" class="hidden preRename" id="preRename-file-<?= $id;?>" onchange="layout.sel('rename-input-file-<?= $id;?>');" />
<input type="checkbox" class="hidden preRemove" id="preRemove-file-<?= $id;?>" />
<div class="line-box box relative" data-link="object-<?= $id;?>">
	<div class="short-row">
		<div class="hand-mover inline" title="Схватить и переместить страницу" data-class="drag-item" data-id="<?= $id;?>" data-link="<?= $id;?>"></div>
		<div class="mini-image inline" <?= $picture ? 'style="background-image:url('.$picture.'); cursor:zoom-in;" title="Увеличить изображение"' : 'style="background-image:url(icons/mime_'.($type ?:'none').'.svg);" title="Тип файла: '.strtoupper($type).'"';?> <?= $bigPicture ? 'onclick="sf.zoomImg(\''.$bigPicture.'\');"' : '';?>></div>
	</div>
	<div class="short-row info-block">
		<div class="title" title="<?= $title;?>"><?= $title;?></div>
		<div class="info">Дата загрузки: <?= date('d.m.Y', $date);?></div>
		<div class="info">Пользователь: <?= $user;?></div>
		<div class="info">Размер: <?= ceil($size/1024/1024);?> Mb</div>
	</div>
	<div class="short-row actions">
		<label for="preRename-file-<?= $id;?>" class="action inline edit" title="Редактировать название файла"></label>

		<label for="preRemove-file-<?= $id;?>" class="action inline delete" title="Удалить файл"></label>

		<input type="checkbox" id="link-<?= $id;?>" class="hidden item-link" onchange="layout.sel('path-to-file-<?= $id;?>')" />
		<label for="link-<?= $id;?>" class="action inline link relative" title="Показать ссылку">
			<input type="text" value="<?= $this->model->getHref($id);?>" class="abs-link hidden absolute" placeholder="Путь к файлу" id="path-to-file-<?= $id;?>" />
		</label>

		<input type="checkbox" name="checked[]" id="mark-<?= $id;?>" value="<?= $id;?>" class="hidden checker" />
		<label for="mark-<?= $id;?>" class="action inline check" title="Отметить"></label>
	</div>
	<div class="short-row del-form hidden">
		<form action="<?= $this->url.($this->folder ? '/folder-'.$this->folder : '').'/delete-file-'.$id; ?>" name="files-delete-file-form-<?= $id;?>" method="POST" class="quest">
			<input type="hidden" name="_method" value="DELETE" />
			<input type="submit" onclick="myfiles.post(document.forms['files-delete-file-form-<?= $id;?>']); return false;" title="Удалить" class="inline yes icon" />
			<label for="preRemove-file-<?= $id;?>" title="Отмена" class="inline no icon"></label>
		</form>
	</div>
	<div class="short-row rename-form hidden">
		<form action="<?= $this->url.($this->folder ? '/folder-'.$this->folder : '').'/update-title-file-'.$id; ?>" name="files-update-file-form-<?= $id;?>" method="POST" class="quest inline">
			<input type="hidden" name="_method" value="PUT" />
			<input type="text" class="rename-input inline" name="update-title" value="<?= $title;?>" id="rename-input-file-<?= $id;?>" placeholder="Название файла" />
			<input type="submit" onclick="myfiles.post(document.forms['files-update-file-form-<?= $id;?>']); return false;" title="Сохранить" class="inline yes icon" />
			<label for="preRename-file-<?= $id;?>" title="Отмена" class="inline no icon"></label>
		</form>
	</div>
</div>
