<div class="head-line relative">
	<div class="path inline">
		<a class="path-link" href="<?= $this->url;?>" onclick="settings.moveTo(this.href); return false;">Корень</a>
	</div>
	<div class="main-title inline"><?= $this->title;?></div>
</div>
<div class="data-content">
	<nav class="module-menu inline">
		<ul class="ul">
			<?php
				foreach($this->subModules->getAll() as $module) {

					echo $this->render(__DIR__.'/sub-module.tpl', ['module' => $module]);

				}
			?>
		</ul>
	</nav>
	<div class="module-data inline">
		<?= $this->content;?>
	</div>
</div>
