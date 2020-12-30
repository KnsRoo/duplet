<div class="sm-pages">
    <?php $query->page = $pager->getPervPage(); ?>
    <a class="page" href="<?= $action.$query; ?>">◄</a>
    <?php foreach ($pager->genPages() as $page):
    $query->page = $page;
    ?>
    <a href="<?= "${action}${query}"; ?>"
        class="page <?= $page == $pager->getCurrentPage() ? 'active' : ''; ?>">
        <?= $page; ?>
    </a>
    <?php endforeach; ?>
    <?php $query->page = $pager->getNextPage(); ?>
    <a class="page" href="<?= $action.$query; ?>">►</a>
</div>
