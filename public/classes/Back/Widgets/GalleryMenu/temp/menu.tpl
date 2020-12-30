<ul>
    <?php foreach ($items as $item): ?>
    <li>
        <a href="/<?= $item->chpu; ?>"> <?= $item->title; ?> </a>
        <?php
        if (!in_array($item->id, $except))
            echo $getItems($item->id, $deep, $except);
        ?>
    </li>
    <?php endforeach; ?>
</ul>
