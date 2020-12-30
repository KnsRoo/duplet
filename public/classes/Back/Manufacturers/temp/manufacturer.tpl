<?php
    use \Model\FileMan\File;
    use \Back\Files\Config;
?>
<!-- <input type="checkbox" class="hidden preRename" id="preRename-file-<?//= $file->id; ?>" onchange="layout.sel('rename-input-file-<?//= $file->id; ?>');" /> -->
<input type="checkbox" class="hidden preRemove" id="preRemove-<?= $manufacturer->id; ?>" />
<div class="line-box box relative" data-link="object-<?= $manufacturer->id;?>">
    <div class="short-row">
        <!-- <div class="hand-mover inline" title="Схватить и переместить" data-class="drag-item" data-id="<?//= $file->id; ?>" data-link="<?//= $file->id; ?>"></div> -->
        <?php
            $file = File::find(['id' => $manufacturer->picture])->get();

            if ($file->isPicture()) {

                $small = $file->getSmallPicture();
                $big = $file->getBigPicture();
                echo ' <div class="mini-image inline"
                    style="'.($small ? 'background-image:url('.Config::PREFIX_PATH.'/'.$small.');' : '').' cursor:zoom-in;"
                    title="Увеличить изображение"
                    onclick="'.($big ? 'sf.zoomImg(\''.Config::PREFIX_PATH.'/'.$big.'\');' : '').'" ></div>
                ';

            } elseif ($file->isVPicture()) {
    
                echo '
                    <div class="mini-image inline"
                        style="background-image:url('.Config::PREFIX_PATH.'/'.$file->getName().'); cursor:zoom-in;"
                        title="Увеличить изображение"
                        onclick="sf.zoomImg(\''.Config::PREFIX_PATH.'/'.$file->getName().'\');" ></div>
                ';

            } elseif ($file->isVideo()) {

                $preview = $file->getVideoPreview('50x50');
                $preview = Config::PREFIX_PATH.'/'.$preview;

                echo '<div class="mini-image inline"
                    style="background-image:url('.$preview.');"
                    title="Тип файла: '.strtoupper($file->ext).'" ></div>';

            }
            else echo '<div class="mini-image inline"
                title="Тип файла: '.strtoupper($file->ext).'" ></div>';
        ?>
    </div>
    <div class="short-row info-block">
        <div class="title" title="<?= $manufacturer->name ;?>"><?= $manufacturer->name ;?></div>
        <div class="info">Ссылка на сайт: <?= $manufacturer->url; ?></div>
    </div>
    <div class="short-row">
        <form name="manufacturer-update-sort-form-<?= $manufacturer->id;?>" action="<?= $this->url.'/update-sort-'.$manufacturer->id; ?>" method="POST"
            onsubmit="manufacturers.post(document.forms['manufacturer-update-sort-form-<?= $manufacturer->id;?>']); return false;">
            <input type="hidden" name="_method" value="PUT" />
            <input type="text" class="num" name="sort" value="<?= (int)$manufacturer->sort;?>" title="Порядковый номер" />
        </form>
    </div>
    <div class="short-row actions">

        <a href="<?= $this->url.'/update-manufacturer-'.$manufacturer->id; ?>/?page=<?= $this->page; ?>" onclick="manufacturers.moveTo(this.href); return false;" class="action inline edit" title="Редактировать производителя"></a>

        <label for="preRemove-<?= $manufacturer->id; ?>" class="action inline delete" title="Удалить"></label>

    </div>
    <div class="short-row del-form hidden">

        <form action="<?= $this->url.'/delete-'.$manufacturer->id; ?>" name="manufacturers-delete-form-<?= $manufacturer->id; ?>" method="POST" class="quest">
            <input type="hidden" name="_method" value="DELETE" />
            <input type="submit" onclick="manufacturers.post(document.forms['manufacturers-delete-form-<?= $manufacturer->id; ?>']); return false;" title="Удалить" class="inline yes icon" />
            <label for="preRemove-<?= $manufacturer->id; ?>" title="Отмена" class="inline no icon"></label>
        </form>

    </div>
</div>
