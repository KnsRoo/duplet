<li data-channel-name="cat" data-subscribe="cat" data-message='{"id": "<?= $catalog->id; ?>", "type": "cat"}' class="item relative <?= $catalog->visible ? '' : 'unvis'; ?>" data-id="<?= $catalog->id; ?>" data-class="drag-drop-place">
    <a href="<?='catalog/cat-'.$catalog->id;  ?>" onclick="catalog.moveTo(this.href); return false;" class="link-with-icons inline"><?= $catalog->title; ?></a>
</li>
