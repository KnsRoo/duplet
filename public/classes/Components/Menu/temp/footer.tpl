<div class="footer__menu">
	<div class="footer__menu_title">Меню</div>
	<div class="footer__menu_block">
		<ul class="footer__links">
			<?php foreach ($pages as $index => $page) : ?>
				<?php if (($index % 2) == 0) : ?>
					<li><a class="footer__link" href="<?= $page->chpu ?>"><?= $page->title ?></a></li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
		<ul class="footer__links">
			<?php foreach ($pages as $index => $page) : ?>
				<?php if (($index % 2) != 0) : ?>
					<li><a class="footer__link" href="<?= $page->chpu ?>"><?= $page->title ?></a></li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	</div>
</div>