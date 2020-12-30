<?php
    use Back\Files\Config as FilesConfig;
?>
<input type="checkbox" class="hidden preRemove" id="preRemove-slide-<?= $id;?>" />
<div class="slide relative box <?= $visible ? '' : 'unvis';?><?= ($id == $this->updateSlide) ? 'edit' : '';?>" data-link="object-<?= $id;?>">
    <div class="actions">
        <form name="slides-update-sort-slide-form-<?= $id;?>" action="<?= $this->url.'/update-sort-slide-'.$id; ?>" method="POST" class="inline"
            onsubmit="slides.post(document.forms['slides-update-sort-slide-form-<?= $id;?>']); return false;">
            <input type="hidden" name="_method" value="PUT" />
            <input type="text" class="num" name="sort" value="<?= (int)$sort;?>" title="Порядковый номер слайда" />
        </form>

        <a href="<?= $this->url.'/update-slide-'.$id; ?>" onclick="slides.moveTo(this.href); return false;" class="action inline edit" title="Редактировать слайд"></a>

        <form action="<?= $this->url.'/update-visibility-slide-'.$id; ?>" name="slides-update-visibility-slide-form-<?= $id;?>" method="POST" class="inline action">
            <input type="hidden" name="_method" value="PUT" />
            <input type="submit" onclick="slides.post(document.forms['slides-update-visibility-slide-form-<?= $id;?>']); return false;" class="action inline visible" title="Видимость слайда" />
        </form>

        <label for="preRemove-slide-<?= $id;?>" class="action inline delete" title="Удалить слайд"></label>

        <input type="checkbox" name="checked[]" id="mark-<?= $id;?>" value="<?= $id;?>" class="hidden checker" />
    </div>
    <div class="del-form hidden">
        <form action="<?= $this->url.'/delete-slide-'.$id; ?>" name="slides-form-<?= $id;?>" method="POST" class="quest">
            <input type="hidden" name="_method" value="DELETE" />
            <input type="submit" onclick="slides.post(document.forms['slides-form-<?= $id;?>']); return false;" value="" title="Удалить" class="inline yes icon" />
            <label for="preRemove-slide-<?= $id;?>" title="Отмена" class="inline no icon"></label>
        </form>
    </div>

    <label class="image" <?= $picture ? 'style="background-image:url('.FilesConfig::PREFIX_PATH.'/'.$picture.');"' : '';?>></label>
    <div class="slide-body">
        <div class="title">Заголовок: <?= $title ?: 'Нет заголовка у слайда';?></div>
        <div class="descr"><?= $preview ?: 'Нет текста у слайда';?></div>
        <a href="<?= $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$link;?>" target="_blank" class="link inline"><?= $link ?: 'Нет ссылки у слайда';?></a>
    </div>
</div>
