<div class="head-line relative">
    <div class="path inline">
        <a class="path-link" href="<?= $this->url; ?>" onclick="gallery.moveTo(this.href); return false;">Корень</a>
        <?= $this->newPath; ?>
    </div>
    <div class="main-title inline"><?= $this->title; ?></div>
</div>
<div class="data-content">
    <div class="module-data inline">

        <div class="sm-frame">
            <div class="head blue flex">

                <a href="<?= $rDefault->getBasePath(); ?>"
                    class="btm-block <?= $photos; ?>">
                    <div class="btm-title">все фото</div>
                </a>

                <a href="<?= $rRenderAlbums->getAbsolutePath(); ?>"
                    class="btm-block <?= $albums; ?>">
                    <div class="btm-title">все альбомы</div>
                </a>

                <?php if ($this->permitions['creating-album'] == 'on'): ?>
                    <a href="<?= $rAddAlbum->getAbsolutePath(); ?>"
                        class="btm-block <?= $createAlbums; ?>">
                        <div class="btm-title">создать альбом</div>
                    </a>
                <?php endif; ?>

                <?php if ($this->permitions['creating-photo'] == 'on'): ?>
                    <a href="<?= $rAddPhoto->getAbsolutePath(); ?>"
                        class="btm-block <?= $createPhotos; ?>">
                        <div class="btm-title">загрузить фото</div>
                    </a>
                <?php endif; ?>

            </div>
            <div class="content">
                <?= $this->content; ?>
            </div>
        </div>

    </div>
</div>
