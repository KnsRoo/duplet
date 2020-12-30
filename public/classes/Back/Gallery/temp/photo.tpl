<div class="block-content">

    <?php
        foreach ($photos as $photo):
        $picture = $photo->getPicture('1000x1000');
        $style = $picture ? 'background-image: url('.$picture.');' : '';
    ?>
    <input type="checkbox" class="hidden preRemove" id="preRemove-<?= $photo->id;?>" />
        <div class="photo  <?= $photo->visible ? '' : 'unvis';?>">
            <div class="parent-block">
                <?php
                    $parent = $photo->getCategory($photo->id);
                    if ($parent->id):
                ?>
                <div class="date"
                    style="font-size: 12px;"
                    title="Перейти в альбом">
                    <a href="<?= $albumOP->getAbsolutePath(['id' => $parent->id]) ?>">
                        Фотография из альбома - <?= $parent->title; ?>
                    </a>
                </div>
                <?php else: ?>
                <div class="date"
                    style="font-size: 12px;"
                    title="Без альбома">
                    Фотография из альбома - Без альбома
                </div>
                <?php endif; ?>
            </div>
            <div class="img" style="<?= $style; ?>">
                <div class="img-title" onclick="sf.zoomImg('<?= $picture; ?>')"><?= $photo->title; ?></div>

                <?php if ($this->permitions['deleting-photo'] == 'on'): ?>


                    <div class="short-row del-form hidden">
                        <form action="<?= $delete->getAbsolutePath(['id' => $photo->id]); ?>"
                            name="gallery-delete-photo-<?= $photo->id;?>"
                            method="POST"
                            class="quest">

                            <input type="hidden" name="_method" value="DELETE" />
                            <label class="label">
                                <input class="checkbox" type="checkbox" name="checkbox-test" value="check-photo">
                                <span class="checkbox-custom"></span>
                                Удалить фото из файлового менеджера?
                            </label>
                            <input type="submit" onclick="gallery.post(document.forms['gallery-delete-photo-<?= $photo->id;?>']); return false;" title="Удалить" class="inline yes icon" />
                            <label for="preRemove-<?= $photo->id;?>" title="Отмена" class="inline no icon"></label>
                        </form>
                    </div>

                <?php endif; ?>

            </div>
            <div class="actions">

                <div class="date">Дата создания альбома:<br>
                    <?= $photo->getDate(); ?>
                </div>

                <?php if ($this->permitions['editing-photo'] == 'on'): ?>
                    <div class="short-row">

                        <form name="gallery-update-sort-photo-<?= $photo->id;?>"
                            action="<?= $sort->getAbsolutePath(['id' => $photo->id]); ?>" method="POST"
                            onsubmit="gallery.post(document.forms['gallery-update-sort-photo-<?= $photo->id;?>']); return false;">

                            <input type="hidden" name="_method" value="PUT" />
                            <input type="text" class="num" name="sort" value="<?= (int)$photo->sort;?>" title="Порядковый номер страницы" />
                        </form>
                    </div>
                <?php endif; ?>

                <div class="actionbox">

                    <?php if ($this->permitions['editing-photo'] == 'on'): ?>
                        <a href="<?= $edit->getAbsolutePath(['id' => $photo->id]); ?>"
                            onclick="gallery.moveTo(this.href); return false;"
                            class="action inline edit" title="Редактировать фотографию"></a>
                    <?php endif; ?>

                    <?php if ($this->permitions['editing-photo'] == 'on'): ?>
                        <form action="<?= $visible->getAbsolutePath(['id' => $photo->id]); ?>"
                            name="gallery-update-visibility-form-<?= $photo->id;?>"
                            method="POST" class="inline action">

                            <input type="hidden" name="_method" value="PUT" />
                            <input type="submit" onclick="gallery.post(document.forms['gallery-update-visibility-form-<?= $photo->id;?>']); return false;" class="action inline visible" title="Видимость страницы" />

                        </form>
                    <?php endif; ?>

                    <?php if ($this->permitions['deleting-photo'] == 'on'): ?>
                        <label for="preRemove-<?= $photo->id; ?>" class="action inline delete lable" title="Удалить страницу"></label>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<?= $pager; ?>
