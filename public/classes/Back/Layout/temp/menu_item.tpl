<li class="item">
	<a class="link <?= ($active ? 'active' : ''); ?>"
		href="<?= strtolower($module->getName());?>"
		onclick="layout.openModule(this);return false;"
		title="<?= $module->getTitle(); ?>">
		<div class="name inline"><?= $module->getTitle(); ?></div>
		<div class="icon inline" style="background-image: url(<?= $module->getIcon(); ?>);"></div>
	</a>
</li>
