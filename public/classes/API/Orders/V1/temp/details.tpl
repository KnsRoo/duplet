<div class='details'>
    <div class="head">
        <div class="head__customer">
            <div class="head__header">Плательщик</div>
            <div class="head__field">
                <div class='head__fieldname'> 
                    ФИО:
                </div>
                <div class='head__fieldvalue'>
                    <?= $props['Фамилия']['value']; ?> <?= $props['Имя']['value']; ?> <?= $props['Отчество']['value']; ?>
                </div>
            </div>
            <?php 
            $phones = isset($props['Телефоны']['value']) ? $props['Телефоны']['value'] : [];
            ?>
            <?php foreach($phones as $phone): ?>
            <div class="head__field">
                <div class='head__fieldname'>
                    Телефон:
                </div>
                <div class='head__fieldvalue'>
                    <?= $phone; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php 
            $emails = isset($props['Электронные почты']['value']) ? $props['Электронные почты']['value'] : [];
            ?>
            <?php foreach($emails as $email): ?>
            <div class="head__field">
                <div class='head__fieldname'>
                    Электронная почта:
                </div>
                <div class='head__fieldvalue'>
                    <?= $email; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="head__delivery">
            <div class="head__header">Доставка</div>
            <div class="head__field">
                <div class='head__fieldname'>
                    Способ:
                </div>
                <div>
                    <?= $props['Способ получения']['value']; ?>
                </div>
            </div>
            <div class="head__field">
                <div class='head__fieldname'>
                    Стоимость:
                </div>
                <?php
                    $deliveryPrice = 0;
                    if (isset($props['Стоимость доставки']['value']))
                    $deliveryPrice = $props['Стоимость доставки']['value'];
                ?>
                <div class='head__fieldvalue'>
                    <?= $deliveryPrice; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="table-order">
        <div class="table-order__row table-order__row_header">
            <div class="table-order__column">№</div>
            <div class="table-order__column">Наименование</div>
            <div class="table-order__column">Количество</div>
            <div class="table-order__column">Цена</div>
            <div class="table-order__column">Скидка</div>
            <div class="table-order__column">Сумма</div>
        </div>
        <?php $i = 1; ?>
        <?php $sum = 0; ?>
        <?php foreach($props['Товары']['value'] as $product): ?>
        <?php 
            $discount = isset($product['discount']) ? $product['discount'] : '0';
        ?>
        <div class="table-order__row">
            <div class="table-order__column"><?= $i; ?></div>
            <div class="table-order__column">
                <?= $product['title']; ?>
            </div>
            <div class="table-order__column">
                <?= $product['count']; ?>
            </div>
            <div class="table-order__column">
                <?= $product['price']; ?>
            </div>
            <div class="table-order__column">
                <?= $discount; ?>
            </div>
            <?php 
                $price = $product['price'] * $product['count'];
                $matches = [];
                $res = preg_match('/^([^%]*)%.*$/', $discount, $matches);
                if ($res) $discount = $price * $matches[1] / 100;
                $price -= $discount;
                $sum += $price;
            ?>
            <div class="table-order__column"><?= $price; ?></div>
        </div>
        <?php ++$i; ?>
        <?php endforeach; ?>
        <div class="table-order__row">
            <div class="table-order__deliverycolumn"><?= $i; ?></div>
            <div class="table-order__deliverycolumn">Доставка</div>
            <div class="table-order__deliverycolumn"></div>
            <div class="table-order__deliverycolumn">
                <?= $deliveryPrice; ?>
            </div>
        </div>
        <div class="table-order__row">
            <div class="table-order__totalcolumn">
                Итого
            </div>
            <div class="table-order__totalcolumn"></div>
            <?php 
                $discount = &$props['Скидка']['value'];
            ?>
            <div class="table-order__totalcolumn">
                <?= $discount; ?>
            </div>
            <?php 
                $sum += $deliveryPrice;

                $res = preg_match('/^([^%]*)%.*$/', $discount, $matches);
                if ($res) $discount = $sum * $matches[1] / 100;
                $sum -= $discount;
            ?>
            <div class="table-order__totalcolumn"><?= $sum; ?></div>
        </div>
    </div>
</div>
