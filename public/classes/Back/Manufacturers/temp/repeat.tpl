<div class="repeat">
    <!-- <?//php if ($this->permitions['creating'] == 'on'): ?> -->
    <form action="<?= $this->url.'/create-manufacturer'; ?>" name="manufacturers-form-new-<?= $id;?>" method="POST" class="add-new inline">
        <input type="text" value="" name="create[name]" placeholder="Название нового производителя" class="text-row inline anim text-focus" />
        <input type="submit" onclick="pages.post(document.forms['manufacturers-form-new-<?= $id;?>']); return false;" class="goodBtn inline" value="Добавить" />
    </form>
    <!-- <?//php endif; ?> -->
    <?= $this->pager; ?>
</div>
