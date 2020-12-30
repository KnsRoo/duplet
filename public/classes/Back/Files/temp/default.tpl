<div class="head-line relative">
	<div class="path inline">
		<?= $this->path; ?>
	</div>
	<div class="main-title inline"><?= $this->title;?></div>
</div>
<div class="data-content">
	<nav class="module-menu inline">
		<ul class="ul">
			<?php
				if (!$this->folder->isNew()) echo '
					<li class="item back" data-id="'.($this->folder->cid ?: '0').'" data-class="drag-drop-place">
						<a href="'.$this->url.($this->folder->cid ? '/folder-'.$this->folder->cid : '').'" onclick="myfiles.moveTo(this.href); return false;" class="link">Назад</a>
					</li>
				';

				foreach($folders->genAll() as $item)
					echo $this->render(__DIR__.'/folder.tpl', ['folder' => $item]);

			?>
		</ul>
		<?php if ($this->permitions['creating-folders'] == 'on'): ?>
		<form action="<?= $this->url.($this->folder->id ? '/folder-'.$this->folder->id : '').'/create-folder'; ?>" name="files-form-new-folder" method="POST" class="add-new relative">
			<input type="text" value="" name="create[title]" placeholder="Название новой папки" class="text-row inline anim text-focus" />
			<input type="submit" onclick="myfiles.post(document.forms['files-form-new-folder']); return false;" class="goodBtn inline" title="Создать" />
		</form>
		<?php endif; ?>
	</nav>
	<div class="module-data inline">

		<?= $this->render(__DIR__.'/repeat.tpl', ['id' => uniqid()]);?>

		<form action="" method="POST">
			<input type="checkbox" id="dop-options" class="hidden check-options" />
			<div class="sorting relative">
				<div class="sort">
					<span div class="name inline">Сортировать по:</span>
					<a href="#" class="var inline active">Дате загрузки</a>
					<a href="#" class="var inline">Названию</a>
					<a href="#" class="var inline">Типу</a>
					<a href="#" class="var inline">Пользователю</a>
					<a href="#" class="var inline">Размеру</a>
				</div>
				<div class="options absolute">
					<label for="dop-options" title="Показать дополнительные опции" class="options-btn"></label>
				</div>

				<?= $this->render(__DIR__.'/popup.tpl');?>

			</div>
		</form>
		<!-- Файлы -->
		<div class="data-place">
			<?php

				foreach($files->genAll() as $file){
					echo $this->render(__DIR__.'/file.tpl', ['file' => $file]);
				}

			?>
		</div>
		<!-- /Файлы -->

		<?= $this->render(__DIR__.'/repeat.tpl', ['id' => uniqid()])?>
	</div>
</div>
