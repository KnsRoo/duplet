<?php

	use Core\Users;

?>

<?php
	foreach (Users::getUsers() as $login => $user) {

		echo '<div class="row">';

		if ($login == $this->editableUser)
			echo $this->render(__DIR__.'/user-item-edit.tpl', ['user' => $user]);

		else 
			echo $this->render(__DIR__.'/user-item.tpl', ['user' => $user]);

		echo '</div>';

	}
?>
