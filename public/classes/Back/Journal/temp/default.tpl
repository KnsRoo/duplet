<?php

use Websm\Framework\Router\Router;
use Core\Users;
//var_dump(Users::getUsers()); die();
$rExport = Router::byName('Journal.export');

?>
<div class="block-title">Журнал работы с панелью</div>
<div class="export-form">
    <form action="<?= $rExport->getAbsolutePath(); ?>" method="GET">
        Выгрузить в CSV за период:
        c <input class="sm-input correction"
            type="text"
            name="date-start"
            value="<?= date('d.m.y'); ?>"
            data-pattern="XX.XX.XX"
            placeholder="дд.мм.гг" />
        по <input class="sm-input correction"
            type="text"
            name="date-end"
            value="<?= date('d.m.y'); ?>"
            data-pattern="XX.XX.XX"
            placeholder="дд.мм.гг" />
        Пользователь:
        <input type="text" list="users" placeholder="Все" name="users" />
        <datalist id="users" name="users">
            <?php foreach(Users::getUsers() as $user): ?>
            <option value="<?= $user->login; ?>">
            <?php endforeach; ?>
        </datalist>
        <input class="sm-button download" type="submit" value="выгрузить" />
    </form>
</div>
<div class="table box">
    <div class="header">
        <div class="zelle inline-m">Дата и время</div>
        <div class="zelle inline-m">Действие</div>
        <div class="zelle inline-m">IP адрес</div>
    </div>
    <?php

        foreach ($this->data as $line) {

            $line->date = preg_replace('/(\d{4})\-(\d{2})\-(\d{2})(\s\d{2}:\d{2}:\d{2})/', '$3.$2.$1 $4', $line->date);
            echo $this->render(__DIR__.'/tableLine.tpl', ['line' => $line]);

        }

    ?>
</div>
