<nav class="nav">
  <ul class="nav__list">
    <?php foreach ($pages as $page) : ?>
    <li class="nav__title"><a class="nav__link" href="<?= $page->chpu ?>"><?= $page->title ?></a></li>
    <?php endforeach ?>
  </ul>
</nav>