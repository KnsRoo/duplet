<?php
    use Websm\Framework\Router\Router;
    use Back\Layout\LayoutModel;
    use Core\Users;
    use Back\Users\Modules;
    use Back\Users\Module;

    $route = Router::instance();

    $currentUser = Users::get();

    $modules = new Modules;

    foreach ($currentUser->modules as $name => $info) {

        $modules->add($name, $info);

    }

?>
<form action="<?= Router::byName('Users.createUser')->getAbsolutePath(); ?>" method="POST">
    <div class="row">
        <span>Логин:</span>
        <input class="sm-input" name="new-user[login]" value="" maxlength="80" placeholder="Логин" type="text">
    </div>
    <div class="row">
        <span>Пароль:</span>
        <input class="sm-input" name="new-user[password]" value="" placeholder="*************" type="password">
    </div>
    <div class="row">
        <span>Подтвердите пароль:</span>
        <input class="sm-input" name="new-user[retype-password]" value="" placeholder="*************" type="password">
    </div>
    <div class="row">
        <span>Номер телефона:</span>
        <input class="sm-input correct" name="new-user[phone]" value="" placeholder="+7 (XXX) XXX-XX-XX" type="text">
    </div>
    <div class="row">
        <span>email:</span>
        <input class="sm-input" name="new-user[email]" value="" placeholder="test-email@yandex.ru" type="text">
    </div>
    <div class="row">
        <span>Права на модули:</span>
        <div class="inline">
            <?php
                foreach ($modules->getAll() as $key => $module):
                    $name = $module->getName();
            ?>
            <div class="module">
                <label class="sm-toggler">
                    <input class="toggle-trigger" name="new-user[modules][<?= $name; ?>]" value="<?= $key; ?>" type="checkbox" />
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
        <input class="sm-button add" value="Добавить" type="submit">
    </div>
</form>
