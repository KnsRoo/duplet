<?php
	use \Back\Files\Config;
?>
<input type="checkbox" class="hidden preRename" id="preRename-file-<?= $file->id; ?>" onchange="layout.sel('rename-input-file-<?= $file->id; ?>');" />
<input type="checkbox" class="hidden preRemove" id="preRemove-file-<?= $file->id; ?>" />
<div class="line-box box relative" data-link="object-<?= $file->id;?>">
	<div class="short-row">
		<div class="hand-mover inline" title="Схватить и переместить" data-class="drag-item" data-id="<?= $file->id; ?>" data-link="<?= $file->id; ?>"></div>
		<?php
			if ($file->isPicture()) {

				$small = $file->getSmallPicture();
				$big = $file->getBigPicture();
				echo ' <div class="mini-image inline"
					style="'.($small ? 'background-image:url('.Config::PREFIX_PATH.'/'.$small.');' : '').' cursor:zoom-in;"
					title="Увеличить изображение"
					onclick="'.($big ? 'sf.zoomImg(\''.Config::PREFIX_PATH.'/'.$big.'\');' : '').'" ></div>
				';

			} elseif ($file->isVPicture()) {
	
				echo '
					<div class="mini-image inline"
						style="background-image:url('.Config::PREFIX_PATH.'/'.$file->getName().'); cursor:zoom-in;"
						title="Увеличить изображение"
						onclick="sf.zoomImg(\''.Config::PREFIX_PATH.'/'.$file->getName().'\');" ></div>
				';

			} elseif ($file->isVideo()) {

				$preview = $file->getVideoPreview('50x50');
				$preview = Config::PREFIX_PATH.'/'.$preview;

				echo '<div class="mini-image inline"
					style="background-image:url('.$preview.');"
					title="Тип файла: '.strtoupper($file->ext).'" ></div>';

			}
			else echo '<div class="mini-image inline"
				style="background-image:url(icons/'.$file->getIcon().');"
				title="Тип файла: '.strtoupper($file->ext).'" ></div>';
		?>
	</div>
	<div class="short-row info-block">
		<div class="title" title="<?= $file->title ;?>"><?= $file->title ;?></div>
		<div class="info">Дата загрузки: <?= date('d.m.Y', $file->date); ?></div>
		<div class="info">Пользователь: <?= $file->user; ?></div>
		<div class="info">Размер: <?= ceil($file->size/1024/1024); ?> Mb</div>
	</div>
	<div class="short-row actions">

		<?php if ($this->permitions['editing-files'] == 'on'): ?>
		<label for="preRename-file-<?= $file->id; ?>" class="action inline edit" title="Редактировать название файла"></label>
		<?php endif; ?>

		<?php if ($this->permitions['deleting-files'] == 'on'): ?>
		<label for="preRemove-file-<?= $file->id; ?>" class="action inline delete" title="Удалить файл"></label>
		<?php endif; ?>

		<input type="checkbox" id="link-<?= $file->id; ?>" class="hidden item-link" onchange="layout.sel('path-to-file-<?= $file->id; ?>')" />
		<label for="link-<?= $file->id; ?>" class="action inline link relative" title="Показать ссылку">
			<input type="text" value="<?= Config::PREFIX_PATH; ?>/<?= $file->id.'.'.$file->ext; ?>" class="abs-link hidden absolute" placeholder="Путь к файлу" id="path-to-file-<?= $file->id; ?>" />
		</label>

		<?php if ($this->permitions['editing-files'] == 'on'): ?>
		<input type="checkbox" name="checked[]" id="mark-<?= $file->id; ?>" value="<?= $file->id; ?>" class="hidden checker" />
		<label for="mark-<?= $file->id; ?>" class="action inline check" title="Отметить"></label>
		<?php endif; ?>

	</div>
	<div class="short-row del-form hidden">

		<?php if ($this->permitions['deleting-files'] == 'on'): ?>
		<form action="<?= $this->url.($this->folder->id ? '/folder-'.$this->folder->id : '').'/delete-file-'.$file->id; ?>" name="files-delete-file-form-<?= $file->id; ?>" method="POST" class="quest">
			<input type="hidden" name="_method" value="DELETE" />
			<input type="submit" onclick="myfiles.post(document.forms['files-delete-file-form-<?= $file->id; ?>']); return false;" title="Удалить" class="inline yes icon" />
			<label for="preRemove-file-<?= $file->id; ?>" title="Отмена" class="inline no icon"></label>
		</form>
		<?php endif; ?>

	</div>
	<div class="short-row rename-form hidden">

		<?php if ($this->permitions['editing-files'] == 'on'): ?>
		<form action="<?= $this->url.($this->folder->id ? '/folder-'.$this->folder->id : '').'/update-title-file-'.$file->id; ?>" name="files-update-file-form-<?= $file->id; ?>" method="POST" class="quest inline">
			<input type="hidden" name="_method" value="PUT" />
			<input type="text" class="rename-input inline" name="update-title" value="<?= $file->title; ?>" id="rename-input-file-<?= $file->id; ?>" placeholder="Название файла" />
			<input type="submit" onclick="myfiles.post(document.forms['files-update-file-form-<?= $file->id; ?>']); return false;" title="Сохранить" class="inline yes icon" />
			<label for="preRename-file-<?= $file->id; ?>" title="Отмена" class="inline no icon"></label>
		</form>
		<?php endif; ?>

	</div>
</div>
