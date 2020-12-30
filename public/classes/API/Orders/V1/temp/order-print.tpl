<?php 

$props = json_decode($order->props, true);
$date = new \DateTime;
$day = $date->format('d');

$current = setlocale(LC_TIME, null);
setlocale(LC_TIME, 'ru_RU.UTF-8');

$format = '%d %B %Y';
$date = strftime($format, $date->format('U'));

setlocale(LC_TIME, $current);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/css/pdf.css">
    <title>Чек</title>
</head>
<body>
    <div class='header'>
        <div class='header__kskyamal-logo'></div>
        <div class='header__address'>629806, ЯНАО, г. Ноябрьск, ул. Советская, 51 629806, ЯНАО, г. Ноябрьск, ул. Энтузиастов, 43</div>
    </div>
    <div class='bill-info'> 
        Билет No <?= $props['Код']['value']; ?> от  <?= $date; ?> года.
    </div>
    <div class='customer-info'> 
        <?= $this->render(__DIR__ . '/details.tpl', ['props' => $props]); ?>
    </div>
    <!-- <div class='footer'> -->
    <!--     <div class='footer__signature'> --> 
    <!--         <div>Руководитель предприятия:</div> -->
    <!--         <div>_________________________</div> -->
    <!--         <div>(<?= ''; ?>)</div> -->
    <!--     </div> -->
    <!-- </div> -->
</body>
</html>
