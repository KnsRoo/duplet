<?php
	$this->css[] = 'css/users.css';
	$this->js[] = 'js/users.js';
	$this->js[] = 'plugins/corrector.js/corrector.min.js';
?>
<div class="add-new-user">
	<h2>Добавление нового пользователя</h2>
	<?= $this->render(__DIR__.'/add-new-user.tpl'); ?>
</div>
<div class="all-users">
	<h2>Существующие пользователи</h2>
	<?= $this->render(__DIR__.'/all-users.tpl'); ?>
</div>
