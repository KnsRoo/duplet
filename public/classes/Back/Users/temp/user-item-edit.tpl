<?php

    use Websm\Framework\Router\Router;
    use Core\Users;
    use Back\Users\Module;
    use Back\Users\Modules;

    $updateRoute = Router::byName('Users.updateUser');
    $baseRoute = Router::byName('Users.baseAction');

    $currentUser = Users::get();

    $allModules = array_merge($currentUser->modules, $user->modules);

    $modules = new Modules;

    foreach ($allModules as $name => $info) {

        $modules->add($name, $info);

    }

?>
<div class="sm-edit-frame">

    <div class="head blue">
        <?= $user->login; ?>
        <a class="closer" href="<?= $baseRoute->getAbsolutePath(); ?>">x</a>
    </div>
    <div class="content">

        <form action="<?= $updateRoute->getAbsolutePath(['user' => $user->login]); ?>" method="POST">
            <input type="hidden" name="_method" value="PUT" />
            <div class="row">
                <span>Логин:</span>
                <input class="sm-input" name="update-user[login]" maxlength="80" value="<?= $user->login; ?>" placeholder="Логин" type="text">
            </div>
            <div class="row">
                <span>Пароль:</span>
                <input class="sm-input" name="update-user[password]" value="<?= $user->password; ?>" placeholder="*************" type="password">
            </div>
            <div class="row">
                <span>Подтвердите пароль:</span>
                <input class="sm-input" name="update-user[retype-password]" value="<?= $user->password; ?>" placeholder="*************" type="password">
            </div>
            <div class="row">
                <span>Номер телефона:</span>
                <input class="sm-input correct" name="update-user[phone]" value="<?= $user->phone; ?>" placeholder="+7 (XXX) XXX-XX-XX" type="text">
            </div>
            <div class="row">
                <span>email:</span>
                <input class="sm-input" name="update-user[email]" value="<?= $user->email; ?>" placeholder="test-email@yandex.ru" type="text">
            </div>
            <div class="row">
                <span>Права на модули:</span>
                <div class="inline">
                    <?php foreach($modules->getAll() as $module): ?>
                    <div class="row">
                        <label class="sm-toggler">
                            <input class="toggle-trigger"
                                name="update-user[modules][<?= $module->getName(); ?>]"
                                type="checkbox"
                                <?= isset($user->modules[$module->getName()]) ? 'checked' : ''; ?>
                                />
                            <div class="icon"></div>
                            <?= $module->getTitle(); ?>
                        </label>

                        <?php
                            if ($settings = $module->getSettingsContent('', [])):
                            $id = uniqid();
                        ?>

                        <div class="sm-options-frame to-left">
                            <input id="options-frame-trigger-to-right-<?= $id; ?>" class="options-frame-trigger" type="checkbox">
                            <label for="options-frame-trigger-to-right-<?= $id; ?>" class="opener"></label>

                            <div class="frame">
                                <div class="head">
                                    <label for="options-frame-trigger-to-right-<?= $id; ?>" class="closer"></label>
                                    Права
                                </div>
                                <div class="content"> <?= $settings; ?> </div>
                            </div>
                        </div>

                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row">
                <input class="sm-button save" value="Сохранить" type="submit">
            </div>
        </form>

    </div>

</div>
