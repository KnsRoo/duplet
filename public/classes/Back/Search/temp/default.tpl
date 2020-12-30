<?php
    use Websm\Framework\Router\Router;

    $rSearch = Router::byName('Search.search');
?>
<div class="head-line relative">
    <div class="path inline">
        <a class="path-link" href="<?= $this->url;?>" onclick="settings.moveTo(this.href); return false;">Корень</a>
    </div>
    <div class="main-title inline"><?= $this->title;?></div>
</div>
<div class="data-content">
    <div class="module-data inline" style="width: 100%;">

        <form action="<?= $rSearch->getAbsolutePath(); ?>" method="GET">
            <div class="search-block">
                <div class="mini-title">Поиск:</div>
                <input class="sm-input"
                    oninput="search.getResults(this.value);" 
                    name="query" placeholder="Я ищу..."
                    type="text" autocomplete="off">
                <input class="sm-button mini" value="Поиск" type="submit">
            </div>
            <div id="search-results" class="hidden"></div>
        </form>

        <div class="sm-frame">
            <div class="head"> Результаты поиска: </div>
            <div class="content" style="width: 100%;">

                <?= $this->resultForm; ?>

            </div>
        </div>

    </div>
</div>
