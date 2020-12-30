<!DOCTYPE html>
<html lang="ru_RU">
    <head>
        <base href="<?= _PWD.'/';?>" />
        <meta charset="UTF-8" />
        <meta name="author" content="Ltd Scarlett & Monarh" />
        <meta name="copyright" content="Панель управления сайтом" />
        <title>Панель управления сайтом</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="stylesheet" href="css/login.css" />
    </head>
    <body>
        <div class="form">
            <img class="logo" src="icons/logo.svg" alt="Ltd Scarlett & Monarh" title="Ltd Scarlett & Monarh" />
            <form method="POST" action="login" >
                <div class="login">
                    <input type="text" name="login" value="" placeholder="Логин" required/>
                </div>
                <div class="password">
                    <input type="password" name="password" value="" placeholder="Пароль" required/>
                </div>
                <input type="submit" name="send" value="Войти" />
            </form>
        </div>
        <script src="js/min/sf.js"></script>
        <script>
            <?php
                while($data = \Websm\Framework\Notify::shift())
                    echo 'sf.alert(\''.$data['text'].'\', \''.$data['type'].'\');';
            ?>
        </script>
    </body>
</html>
