<?php
   use Core\Misc\MiniPager\MiniPager;
?>
<div class="files-content">
    <div class="path">
        <?= $this->path;?>
    </div>
    <?= $this->folders;?>
    <?php

        foreach($folders as $folder) {

            echo $this->render(__DIR__.'/folder.tpl', [ 'folder' => $folder ]);

        }

    ?>

    <?php if ($this->permitions['uploading-files'] == 'on'): ?>
    <input id="file-<?= $this->uniqid; ?>"
        class="hidden"
        type="file"
        name="files[]"
        onchange="this.parentNode.parentNode.filesMin.uploadFiles(this);"
        multiple />

    <label for="file-<?= $this->uniqid; ?>"
        class="uploadBtn goodBtn"
        title="Загрузить файл"></label>
    <?php endif; ?>

    <?php if($this->options->optional):?>

        <input 
            type="<?= $this->options->multiple ? 'checkbox' : 'radio'; ?>"
            class="hidden"
            name="file-<?= $this->uniqid; ?><?= $this->options->multiple ? '[]' : ''; ?>"
            value=""
            id="mini-file-empty-<?= $this->uniqid; ?>"
            <?php

            $onchange = '';

            if ($this->options->setValue) {
                $onchange .= 'FilesMin.setValue(\''.$this->options->setValue.'\', this.value);';
            }

            $onchange .= $this->options->onchange;

            ?>
            onchange="<?= $onchange; ?>" 
        />
        <label <?= $this->options->checkable ? 'for="mini-file-empty-'.$this->uniqid.'"' : ''; ?> class="file-block" title="Пусто">
            <div class="file-block-icon"></div>
            <div class="file-block-title" title="Пусто">Пусто</div>
        </label>

    <?php endif; ?>

    <?php


    foreach($files as $file) {

        echo $this->render(__DIR__.'/file.tpl', [ 'file' => $file ]);

    }

    ?>

</div>
<div class="fot">

    <?php

    echo MiniPager::makePager(
    $this->allItems,
    $this->itemsOnPage,
    $this->currentPage,
    'this.parentNode.parentNode.parentNode.filesMin.cwd(\''.$this->folder->id.'\', :page)'
    );

    ?>
</div>
<br />
