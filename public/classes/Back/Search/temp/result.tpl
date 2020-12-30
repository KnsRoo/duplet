<div class="search-result" style="display: flex;flex-wrap: wrap;">
    <?php foreach ($result as $res): ?>
    <div class="test">
        <?= $res; ?>
    </div>
    <?php endforeach; ?>
</div>
<div class="repeat" style="text-align: center;">
    <div class="pager inline">
        <?= $pager; ?>
    </div>
</div>
