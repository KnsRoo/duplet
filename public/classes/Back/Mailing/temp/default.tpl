<?php

use Core\Router\Router;

$rDefaultAction = Router::byName('Mailing.defaultAction')
    ->getAbsolutePath();

?>
<div class="head-line relative">
    <div class="path inline">
        <a class="path-link" href="<?= $rDefaultAction; ?>" onclick="orders.moveTo(this.href); return false;">Корень</a>
    </div>
    <div class="main-title inline">Рассылка</div>
</div>
<div class="data-content">
    <div class="module-data inline">

    </div>
</div>

