<div class="head-line relative">
    <!-- <div class="path inline"> -->
    <!--     <?//= $this->path; ?> -->
    <!-- </div> -->
    <div class="main-title inline"><?= $this->title;?></div>
</div>
<div class="data-content">
    <div class="module-data inline">

        <?= $this->render(__DIR__.'/repeat.tpl', ['id' => uniqid()]);?>

        <!-- <div class="data-place"> -->
            <?php

                foreach($manufacturers as $manufacturer){
                    echo $manufacturer->id == $this->update
                        ? $this->render(__DIR__.'/manufacturer_edit.tpl', ['manufacturer' => $manufacturer])
                        : $this->render(__DIR__.'/manufacturer.tpl', ['manufacturer' => $manufacturer]);
                }

            ?>
        <!-- </div> -->

    </div>
</div>
