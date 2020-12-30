<div class="head-line relative">
	<div class="path inline">
		<a class="path-link" href="<?= $this->url;?>" onclick="pages.moveTo(this.href); return false;">Корень</a>
		<?=$this->newPath?>
	</div>
	<div class="main-title inline"><?= $this->title;?></div>
</div>
<div class="data-content">
	<nav class="module-menu inline">
		<ul class="ul">
			<?php
				if ($this->page && $this->page != $this->permitions['chroot']) {
					echo '
						<li class="item back" data-id="'.($this->parent ?: '0').'" data-class="drag-drop-place">
							<a href="'.$this->url.($this->parent ? '/page-'.$this->parent : '').'" onclick="pages.moveTo(this.href); return false;" class="link">Назад</a>
						</li>
					';
					foreach($this->leftItems as $item)
						echo $this->render(__DIR__.'/left_item.tpl', (Array)$item);
				}
				else echo '
					<li class="item active">
						<a href="'.$this->url.'" onclick="pages.moveTo(this.href); return false;" class="link">Корень</a>
					</li>
				';

			?>
		</ul>
		<?php if ($this->permitions['creating'] == 'on'): ?>
		<?php
			if ($this->page && $this->page != $this->permitions['chroot']) echo '
				<form action="'.$this->url.($this->parent ? '/page-'.$this->parent : '').'/create-page" name="pages-form-new" method="POST" class="add-new relative">
					<input type="text" value="" name="create[title]" placeholder="Название новой подстраницы" class="text-row inline anim text-focus" />
					<input type="submit" onclick="pages.post(document.forms[\'pages-form-new\']); return false;" class="goodBtn inline" title="Создать" />
				</form>
			';
		?>
		<?php endif; ?>
	</nav>
	<div class="module-data inline">

		<?php
			if ($this->page) {

				if ($this->page == $this->update)
					echo $this->render(__DIR__.'/right_item_edit.tpl', ['page' => $this->pageObject]);

				else echo $this->render(__DIR__.'/right_item_self.tpl', ['page' => $this->pageObject]);

			}
		?>

		<?= $this->render(__DIR__.'/repeat.tpl', ['id' => uniqid()]);?>

		<form action="" method="POST">
			<input type="checkbox" id="dop-options" class="hidden check-options" />
			<div class="line-box headers relative">
				<div class="short-row inline">
					<a href="#" onclick="return false;" title="Сортировать по заголовку">Заголовок</a>
				</div>
				<div class="short-row inline">
					<input type="button" value="Описание" />
				</div>
				<div class="short-row inline">
					<a href="#" onclick="return false;" title="Сортировать по дате">Дата</a>
				</div>
				<div class="short-row inline">
					<a href="#" onclick="return false;" title="Сортировать по порядковому номеру">№</a>
				</div>
				<div class="short-row inline">
					<input type="button" value="Действия" />
				</div>
				<!-- <div class="options absolute">
					<label for="dop-options" title="Показать дополнительные опции" class="options-btn"></label>
				</div> -->

				<?= $this->render(__DIR__.'/popup.tpl');?>

			</div>
		</form>
		<?php
			foreach($this->pages as $item)
				echo $item->id == $this->update
					? $this->render(__DIR__.'/right_item_edit.tpl', ['page' => $item])
					: $this->render(__DIR__.'/right_item.tpl', ['page' => $item]);
		?>
	</div>
</div>
