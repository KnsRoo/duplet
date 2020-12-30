<tr>
    <td colspan="1" style="width: 1%; padding-top: 5px; padding-bottom: 25px; border-bottom: 1px solid black;">
        <img src="https://trick.1mcg.ru/<?= $tovar['picture']; ?>" alt="Изображение товара" width="100" height="100" style="display: block;" />
    </td>
    <td colspan="1" style="width: 15%; padding-top: 5px; padding-bottom: 25px; border-bottom: 1px solid black;">
        <?= $tovar['title']; ?>
    </td>
    <td colspan="1" style="text-align: center; width: 5%; padding-top: 5px; padding-bottom: 25px; border-bottom: 1px solid black;">
        <?= $tovar['count']; ?>
    </td>
    <td colspan="1" style="text-align: center; width: 5%; padding-top: 5px; padding-bottom: 25px; border-bottom: 1px solid black;">
        <?= $tovar['price']; ?>
    </td>
    <td colspan="1" style="text-align: center; width: 5%; padding-top: 5px; padding-bottom: 25px; border-bottom: 1px solid black;">
        <?= $tovar['count'] * $tovar['price']; ?>
    </td>
</tr>
