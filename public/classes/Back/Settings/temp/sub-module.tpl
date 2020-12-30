<li class="item <?= $module->getName() == $this->nameSubModule ? 'active' : '';?>" >
	<a href="<?= $this->url.'/'.strtolower($module->getName());?>"
	onclick="settings.moveTo(this.href); return false;"
	class="link"><?= $module->getTitle(); ?></a>
</li>
