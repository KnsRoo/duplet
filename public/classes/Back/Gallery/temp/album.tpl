<div class="block-content">

    <?php
        foreach ($albums as $album):
        $picture = $album->getPicture('1000x1000');
        $style = $picture ? 'background-image: url('.$picture.');' : '';
    ?>
    <input type="checkbox" class="hidden preRemove" id="preRemove-<?= $album->id;?>" />
        <div class="photo  <?= $album->visible ? '' : 'unvis';?>">

            <?php $count = $album->getPhotosCount($album->id); ?>

            <div class="actions">

                <div class="date active-photo">
                    Кол-во фотографий в альбоме - <?= (int)$count; ?>
                </div>

                <span class="span" title="Просмотр альбомов">
                    <a href="<?= $renderAID->getAbsolutePath(['id' => $album->id]); ?>"
                        class="sub-span">
                        В альбом
                    </a>
                </span>
            </div>

            <div class="img" style="<?= $style; ?>">

                <a class="img-title" href="<?= $albumOP->getAbsolutePath(['id' => $album->id]) ?>">
                    Просмотр фотографий в альбоме - <?= $album->title; ?>
                </a>

                <?php if ($this->permitions['deleting-photo'] == 'on'): ?>

                    <div class="short-row del-form hidden">
                        <form action="<?= $delete->getAbsolutePath(['id' => $album->id]); ?>" name="gallery-delete-photo-<?= $album->id;?>" method="POST" class="quest">
                            <input type="hidden" name="_method" value="DELETE" />
                            <label class="label">Удалить альбом?</label>
                            <input type="submit" onclick="gallery.post(document.forms['gallery-delete-photo-<?= $album->id;?>']); return false;" title="Удалить" class="inline yes icon" />
                            <label for="preRemove-<?= $album->id;?>" title="Отмена" class="inline no icon"></label>
                        </form>
                    </div>

                <?php endif; ?>

            </div>
            <div class="actions">

                <div class="date">Дата создания альбома:<br>
                    <?= $album->getDate(); ?>
                </div>

                <?php if ($this->permitions['editing-photo'] == 'on'): ?>
                    <div class="short-row">
                        <form name="gallery-update-sort-photo-<?= $album->id;?>"
                            action="<?= $sort->getAbsolutePath(['id' => $album->id]); ?>" method="POST"
                            onsubmit="gallery.post(document.forms['gallery-update-sort-photo-<?= $album->id;?>']); return false;">
                            <input type="hidden" name="_method" value="PUT" />
                            <input type="text" class="num" name="sort" value="<?= (int)$album->sort;?>" title="Порядковый номер страницы" />
                        </form>
                    </div>
                <?php endif; ?>

                <div class="actionbox">

                    <?php if ($this->permitions['editing-photo'] == 'on'): ?>
                        <a href="<?= $edit->getAbsolutePath(['id' => $album->id]); ?>"
                            onclick="gallery.moveTo(this.href); return false;"
                            class="action inline edit" title="Редактировать фотографию"></a>
                    <?php endif; ?>

                    <?php if ($this->permitions['editing-photo'] == 'on'): ?>
                    <form action="<?= $visible->getAbsolutePath(['id' => $album->id]); ?>"
                            name="gallery-update-visibility-form-<?= $album->id;?>"
                            method="POST" class="inline action">

                            <input type="hidden" name="_method" value="PUT" />
                            <input type="submit" onclick="gallery.post(document.forms['gallery-update-visibility-form-<?= $album->id;?>']); return false;" class="action inline visible" title="Видимость страницы" />

                        </form>
                    <?php endif; ?>

                    <?php if ($this->permitions['deleting-photo'] == 'on'): ?>
                        <label for="preRemove-<?= $album->id; ?>" class="action inline delete" title="Удалить страницу"></label>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>
