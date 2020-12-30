<?php
    use Websm\Framework\Router\Router;
    use Core\Users;

    $editRoute = Router::byName('Users.editUser');
    $deleteRoute = Router::byName('Users.deleteUser');

    $currentUser = Users::get();

    $phone = preg_replace('/^(\d{3})(\d{3})(\d{2})(\d{2})$/', '+7 ($1) $2-$3-$4', $user->phone);

?>
<div class="sm-item">
    <div class="login"><?= $user->login; ?></div>
    <div class="phone"><?= $phone; ?></div>
    <div class="actions">
        <a class="sm-action-button edit"
            href="<?= $editRoute->getAbsolutePath(['user' => $user->login]); ?>"
            title="редактировать">Редактировать</a>
        <?php if ($currentUser->login != $user->login): ?>
        <form method="POST" action="<?= $deleteRoute->getAbsolutePath(['user' => $user->login]); ?>">
            <input type="hidden" name="_method" value="DELETE" />
            <input class="sm-action-button delete" title="удалить" type="submit"
            onclick="return confirm('подтвердите удаление пользователя.');" />
        </form>
        <?php endif; ?>
    </div>
</div>
