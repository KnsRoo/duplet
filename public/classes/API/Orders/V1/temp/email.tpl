<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
    <title>Трик-фарма</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
</head>

<body style="margin: 0; padding: 0;">
    <table cellpadding="0" cellspacing="0" width="100%" style="padding-bottom: 20px;">
        <tr>
            <th colspan="5" style="text-align: left;  background-color: gray;">Заказ на поставку из аптеки<br><?= $pharmacy; ?></th>
        </tr>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="2" style="width: 50%;">Номер заказа:</td>
            <td colspan="3"><?= $id; ?></td>
        </tr>
        <tr>
            <td colspan="2">Дата заказа:</td>
            <td colspan="3"><?= $date; ?></td>
        </tr>
        <tr>
            <td colspan="2">Статус заказа:</td>
            <td colspan="3">Сформирован</td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" width="100%" style="padding-bottom: 20px;">
        <tr>
            <th colspan="5" style="text-align: left;  background-color: gray;">Информация о клиенте</th>
        </tr>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="2" style="width: 50%;">Полное имя (Ф.И.О.):</td>
            <td colspan="3"><?= $fio; ?></td>
        </tr>
        <tr>
            <td colspan="2">Телефон:</td>
            <td colspan="3">+<?= $phone; ?></td>
        </tr>
        <tr>
            <td colspan="2">E-mail:</td>
            <td colspan="3"><?= $email; ?></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" width="100%" style="padding-bottom: 20px;">
        <tr>
            <th colspan="5" style="text-align: left;  background-color: gray;">Товары </th>
        </tr>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <!--header        -->
        <tr>
            <td colspan="2" style="font-weight: bold; padding-top: 5px; padding-bottom: 5px; border-top: 1px solid black; border-bottom: 1px solid black;">Название</td>
            <td colspan="1" style="text-align: center; font-weight: bold; padding-top: 5px; padding-bottom: 5px; border-top: 1px solid black; border-bottom: 1px solid black;">Количество</td>
            <td colspan="1" style="text-align: center; font-weight: bold; padding-top: 5px; padding-bottom: 5px; border-top: 1px solid black; border-bottom: 1px solid black;">Цена со скидкой</td>
            <td colspan="1" style="text-align: center; font-weight: bold; padding-top: 5px; padding-bottom: 5px; border-top: 1px solid black; border-bottom: 1px solid black;">Сумма с учетом скидки</td>
        </tr>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <!--data-->
        <?= $tovars; ?>
        <!--отступы-->
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="font-weight: bold; text-align: right;">Всего к оплате: <?= $count; ?> руб.</td>
        </tr>
        <?php if($comment): ?>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="1" style="text-align: left;">Комментарий к заказу:</td>
            <td colspan="4" style="text-align: left; padding-left: 5%; padding-right: 5%;"><?= $comment; ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="font-weight: bold; text-align: left;">Внимание! Цена и кол-во товара может измениться. </td>
        </tr>
        <tr>
            <td colspan="5" style="width: 100%; height: 15px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="font-weight: bold; text-align: left;">Уведомление о сформированном заказе (состав и итоговая стоимость) вы получите по email.</td>
        </tr>
    </table>
</body>

</html>
