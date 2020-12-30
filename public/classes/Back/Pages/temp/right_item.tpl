<div id="right-line-place-<?= $page->id;?>">
    <input type="checkbox" class="hidden preRemove" id="preRemove-<?= $page->id;?>" />
    <div class="line-box relative box <?= $page->visible ? '' : 'unvis';?>" data-link="object-<?= $page->id;?>">
        <div class="short-row">
            <?php if ($this->permitions['editing'] == 'on'): ?>
            <div class="hand-mover inline" title="Схватить и переместить страницу" data-class="drag-item" data-id="<?= $page->id;?>" data-link="<?= $page->id;?>"></div>
            <?php endif; ?>
            <div class="title inline"><?= $page->title;?></div>
        </div>
        <div class="short-row">
            <?= $page->text ? \Websm\Framework\StringF::cut($page->text, 10).' ...' : '---';?>
        </div>
        <div class="short-row">
            <?= $page->getDate();?>
        </div>
        <div class="short-row">
            <form name="pages-update-sort-form-<?= $page->id;?>" action="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/update-sort-'.$page->id; ?>" method="POST"
                onsubmit="pages.post(document.forms['pages-update-sort-form-<?= $page->id;?>']); return false;">
                <input type="hidden" name="_method" value="PUT" />
                <input type="text" class="num" name="sort" value="<?= (int)$page->sort;?>" title="Порядковый номер страницы" 
                    <?php if ($this->permitions['editing'] != 'on'): ?>
                        disabled
                    <?php endif; ?>
                />
            </form>
        </div>
        <div class="short-row actions">
            <?php if ($this->permitions['editing'] == 'on'): ?>
            <!-- <a href="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/update-page-'.$page->id; ?>/?page-number=<?= $this->pageNumber; ?>"onclick="pages.moveTo(this.href); return false;" class="action inline edit" title="Редактировать страницу"></a> -->
            
            <!-- Чтобы работало добавление свойств. Обработчик клика по умолчанию -->
            <a href="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/update-page-'.$page->id; ?>/?page-number=<?= $this->pageNumber; ?>"
                class="action inline edit" title="Редактировать страницу"></a>
            <?php endif; ?>

            <?php if ($this->permitions['presentation'] == 'on'): ?>
            <form action="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/update-visibility-'.$page->id; ?>" name="pages-update-visibility-form-<?= $page->id;?>" method="POST" class="inline action">
                <input type="hidden" name="_method" value="PUT" />
                <input type="submit" onclick="pages.post(document.forms['pages-update-visibility-form-<?= $page->id;?>']); return false;" class="action inline visible" title="Видимость страницы" />
            </form>
            <?php endif; ?>

            <?php if ($this->permitions['deleting'] == 'on'): ?>
            <?= !$page->static
            ? '<label for="preRemove-'.$page->id.'" class="action inline delete" title="Удалить страницу"></label>'
            : '';?>
            <?php endif; ?>

            <input type="checkbox" id="link-<?= $page->id;?>" class="hidden item-link" onchange="layout.sel('path-fo-page-<?= $page->id;?>')" />
            <label for="link-<?= $page->id;?>" class="action inline link relative" title="Показать ссылку">
                <input type="text" value="<?= $page->chpu;?>" class="abs-link hidden absolute" id="path-fo-page-<?= $page->id;?>" />
            </label>

            <!--input type="checkbox" name="checked[]" id="mark-<?= $page->id;?>" value="<?= $page->id;?>" class="hidden checker" />
            <label for="mark-<?= $page->id;?>" class="action inline check" title="Отметить"></label-->
        </div>
        <div class="short-row del-form hidden">
            <form action="<?= $this->url.($this->page ? '/page-'.$this->page : '').'/delete-page-'.$page->id; ?>" name="pages-delete-form-<?= $page->id;?>" method="POST" class="quest">
                <input type="hidden" name="_method" value="DELETE" />
                <input type="submit" onclick="pages.post(document.forms['pages-delete-form-<?= $page->id;?>']); return false;" title="Удалить" class="inline yes icon" />
                <label for="preRemove-<?= $page->id;?>" title="Отмена" class="inline no icon"></label>
            </form>
        </div>
        <a href="<?= $this->url.'/page-'.$page->id; ?>" onclick="pages.moveTo(this.href); return false;" class="absolute enter-link" title="Перейти в раздел"></a>
    </div>
</div>
