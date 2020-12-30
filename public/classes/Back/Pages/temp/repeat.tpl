<div class="repeat">
	<?php if ($this->permitions['creating'] == 'on'): ?>
	<form action="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/create-page';?>" method="POST" name="pages-form-new-<?= $id;?>" class="add-new inline">
		<input type="text" value="" name="create[title]" placeholder="Название новой страницы" class="text-row inline anim text-focus" />
		<input type="submit" onclick="pages.post(document.forms['pages-form-new-<?= $id;?>']); return false;" class="goodBtn inline" value="Добавить" />
	</form>
	<?php endif; ?>
	<?= $this->pager; ?>
	<!-- <div class="pager inline">
		<div class="pages">
			<a href="#" class="page inline">&#9668;</a>
			<a href="#" class="page inline active">1</a>
			<a href="#" class="page inline">2</a>
			<a href="#" class="page inline">3</a>
			<a href="#" class="page inline dots">...</a>
			<a href="#" class="page inline">99</a>
			<a href="#" class="page inline">&#9658;</a>
		</div>
	</div> -->
</div>
