<!DOCTYPE html>
<html lang="ru-RU">

<head>
    <base href="<?= _PWD . '/'; ?>" />
    <meta charset="UTF-8" />
    <meta name="author" content="Ltd Scarlett & Monarh" />
    <meta name="copyright" content="Панель управления сайтом" />
    <title>Панель управления сайтом</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="css/panel.css" />
    <link rel="stylesheet" href="css/websm-ui.min.css" />
    <?php
    foreach ($this->css as $file) echo '<link rel="stylesheet" href="' . $file . '" />';
    ?>
</head>

<body class="no-margin">
    <div class="absolute" id="ajax-auth-form">
        <form action="/" method="post" class="form relative anim">
            <div class="notify">Время сессии истекло, пожалуйста, подтвердите Ваши данные заново.</div>
            <div class="login">a
                <input type="text" name="login" class="pole" placeholder="Логин" />
            </div>
            <div class="password">
                <input type="password" name="password" class="pole" placeholder="Пароль" />
            </div>
            <input type="button" class="enter" onclick="auth.send();" value="Авторизоваться" />
            <div class="loader absolute hidden">
                <?= file_get_contents('./icons/sf-loader.svg'); ?>
            </div>
        </form>
    </div>
    <noscript class="no-script">У Вас отключен JavaScript. Панель управления сайтом может работать некорректно. Рекомендуем включить JavaScript.</noscript>

    <input type="checkbox" id="menu-roller" class="hidden" />
    <nav class="menu inline absolute anim">
        <ul class="ul main-menu">
            <li class="item">
                <label for="menu-roller" class="link">
                    <div class="name inline">Показать / Скрыть</div>
                    <div class="icon roller inline"></div>
                </label>
            </li>
            <?= $this->menu; ?>
        </ul>
        <ul class="ul system-menu absolute">
            <?= $this->systemMenu; ?>
            <li class="item">
                <form action="exit" method="post">
                    <button name="exit" class="link">
                        <div class="name inline">Выход</div>
                        <div class="icon exit inline"></div>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="absolute" id="content-loader">
        <?= file_get_contents('./icons/sf-loader.svg'); ?>
    </div>
    <div id="layout-content" class="content inline fright relative">
        <div class="layout-wrapper"><?= $this->content; ?></div>
    </div>
    <div class="hidden">
        <?php
        $icons = array_slice(scandir('icons'), 2);
        foreach ($icons as $icon) echo '<img src="icons/' . $icon . '" alt="' . $icon . '" />';
        ?>
    </div>

    <!-- <script src="plugins/tabs.js/public/tabs.min.js"></script> -->
    <script src="js/min/sf.js"></script>
    <script src="js/layout.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/websm-ui.min.js"></script>
    <script src="dist/main.js"></script>
    <?php
    foreach ($this->js as $file) echo '<script src="' . $file . '"></script>';
    while ($data = \Websm\Framework\Notify::shift())
        echo '<script>sf.alert(\'' . $data['text'] . '\', \'' . $data['type'] . '\');</script>';
    ?>
    <script>
        sf.ready(function() {
            layout.getCurrentTab().setOpt('css', <?= json_encode($this->css); ?>);
        });
    </script>
</body>

</html>