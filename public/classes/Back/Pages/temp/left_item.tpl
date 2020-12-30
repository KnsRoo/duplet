<li class="item <?= ($id == $this->page ? 'active' : '');?>" data-id="<?= $id;?>" data-class="drag-drop-place">
	<a href="<?= $this->url.'/page-'.$id; ?>" onclick="pages.moveTo(this.href); return false;" class="link"><?= $title;?></a>
</li>
