<div id="right-line-place-<?= $page->id;?>">
    <div class="line-box relative box <?= $page->visible ? '' : 'unvis';?>" data-link="object-<?= $page->id;?>">
        <div class="short-row">
            <div class="title inline"><?= $page->title;?></div>
        </div>
        <div class="short-row">
            <?= $page->text ? \Websm\Framework\StringF::cut($page->text, 10).' ...' : '---';?>
        </div>
        <div class="short-row">
            <?= $page->getDate();?>
        </div>
        <div class="short-row">
        </div>
        <div class="short-row actions">
            <?php if ($this->permitions['editing'] == 'on'): ?>
            <!-- <a href="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/update-page-'.$page->id; ?>/?page-number=<?= $this->pageNumber; ?>"
                onclick="pages.moveTo(this.href); return false;" class="action inline edit" title="Редактировать страницу"></a> -->
               
                <!-- Чтобы работало добавление свойств. Обработчик клика по умолчанию -->
            <a href="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/update-page-'.$page->id; ?>/?page-number=<?= $this->pageNumber; ?>"
                class="action inline edit" title="Редактировать страницу"></a>
            <?php endif; ?>

            <?php if ($this->permitions['editing'] == 'on'): ?>
            <form action="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/update-visibility-'.$page->id; ?>" name="pages-update-visibility-form-<?= $page->id;?>" method="POST" class="inline action">
                <input type="hidden" name="_method" value="PUT" />
                <input type="submit" onclick="pages.post(document.forms['pages-update-visibility-form-<?= $page->id;?>']); return false;" class="action inline visible" title="Видимость страницы" />
            </form>
            <?php endif; ?>

            <input type="checkbox" id="link-<?= $page->id;?>" class="hidden item-link" onchange="layout.sel('path-fo-page-<?= $page->id;?>')" />
            <label for="link-<?= $page->id;?>" class="action inline link relative" title="Показать ссылку">
                <input type="text" value="/<?= $page->chpu;?>" class="abs-link hidden absolute" id="path-fo-page-<?= $page->id;?>" />
            </label>
        </div>
    </div>
</div>
