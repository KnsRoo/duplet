<div class="head-line relative">
    <div class="path inline">
        <a class="path-link" href="<?= $this->url;?>" onclick="slides.moveTo(this.href); return false;">Корень</a>
    </div>
    <div class="main-title inline"><?= $this->title;?></div>
</div>
<div class="data-content">
    <div class="module-data inline">

        <?= $this->content; ?>

        <div class="data-place">
            <?= $this->render( __DIR__ . '/slide-cart.tpl'); ?>

            <?php
                $slides = \Back\Slides\Models\SlidesModel::find()
                    ->order('`sort`')
                    ->genAll();

                foreach($slides as $slide) {
                    $slide = (Array) $slide;

                    $slide['picture'] = \Back\Files\Picture::get($slide['picture'], '1000x1000');
                    echo $this->render(__DIR__.'/slide.tpl', $slide);
                }
            ?>
        </div>
    </div>
</div>
