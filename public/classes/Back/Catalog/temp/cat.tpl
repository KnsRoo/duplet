<input type="checkbox" class="hidden preRemove" id="preRemove-cat-<?= $catalog->id; ?>" />
<li data-channel-name="cat" data-subscribe="cat" data-message='{"id": "<?= $catalog->id; ?>", "type": "cat"}' class="item relative <?= $catalog->visible ? '' : 'unvis'; ?>" data-id="<?= $catalog->id; ?>" data-class="drag-drop-place">
    <a href="<?= $this->url.'/cat-'.$catalog->id;  ?>" onclick="catalog.moveTo(this.href); return false;" class="link-with-icons inline"><?= $catalog->title; ?></a>
    <div class="actions inline">
        <form name="catalog-update-sort-cat-form-<?= $catalog->id; ?>" action="<?= $this->url.($this->cat ? '/cat-'.$this->cat : '').'/update-sort-cat-'.$catalog->id;  ?>" method="POST" class="inline"
            onsubmit="catalog.post(document.forms['catalog-update-sort-cat-form-<?= $catalog->id; ?>']); return false;">
            <input type="hidden" name="_method" value="PUT" />
            <input type="text" class="num" name="sort" value="<?= (int)$catalog->sort; ?>" title="Порядковый номер категории" />
        </form>

        <form action="<?= $this->url.($this->cat ? '/cat-'.$this->cat : '').'/update-visibility-cat-'.$catalog->id;  ?>" name="catalog-update-visibility-cat-form-<?= $catalog->id; ?>" method="POST" class="inline action">
            <input type="hidden" name="_method" value="PUT" />
            <input type="submit" onclick="catalog.post(document.forms['catalog-update-visibility-cat-form-<?= $catalog->id; ?>']); return false;" class="action inline visible" title="Видимость категории" />
        </form>

        <label for="preRemove-cat-<?= $catalog->id; ?>" class="action inline delete" title="Удалить категорию"></label>
    </div>
    <div class="del-form hidden inline absolute">
        <div class="v-align"></div>
        <form action="<?= $this->url.($this->cat ? '/cat-'.$this->cat : '').'/delete-cat-'.$catalog->id;  ?>" name="catalog-form-<?= $catalog->id; ?>" method="POST" class="quest inline">
            <input type="hidden" name="_method" value="DELETE" />
            <input type="submit" onclick="catalog.post(document.forms['catalog-form-<?= $catalog->id; ?>']); return false;" title="Удалить" class="inline yes icon" />
            <label for="preRemove-cat-<?= $catalog->id; ?>" title="Отмена" class="inline no icon"></label>
        </form>
    </div>
</li>
