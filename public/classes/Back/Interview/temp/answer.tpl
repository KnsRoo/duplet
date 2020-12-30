<?php
use Websm\Framework\Router\Router;

$rUpdateVisAnswer = Router::byName('Interview.updateVisAnswer');
$rDeleteAnswer = Router::byName('Interview.deleteAnswer');
$rUpdateAnswerSort = Router::byName('Interview.updateAnswerSort');
?>
<div id="right-line-place-<?= $answer->id ;?>">
    <input type="checkbox" class="hidden preRemove" id="preRemove-<?= $answer->id ;?>" />
    <div class="line-box answer relative box <?= $answer->visible ? '' : 'unvis' ;?>" data-link="object-<?= $answer->id ;?>">

        <div class="progress" style="width: <?= $percent; ?>%;"></div>

        <div class="short-row text">
            <?= $answer->text ? \Websm\Framework\StringF::cut($answer->text, 10).' ...' : '---' ;?><br />
            Кол-во голосов: <?= $answer->count; ?>
        </div>

        <div class="short-row date"> <?= $answer->getDate(); ?> </div>

        <div class="short-row sort">
            <form action="<?= $rUpdateAnswerSort->getAbsolutePath(['id' => $answer->id]); ?>" method="POST" >
                <input type="hidden" name="_method" value="PUT" />
                <input type="text" class="num" name="sort" value="<?= $answer->sort; ?>" title="Порядковый номер" 
                <?php if ($this->permitions['editing'] != 'on'): ?>
                disabled
                <?php endif; ?>
                />
            </form>
        </div>

        <div class="short-row actions">

            <?php if ($this->permitions['editing'] == 'on'): ?>
            <form action="<?= $rUpdateVisAnswer->getAbsolutePath(['id' => $answer->id]); ?>" method="POST" class="inline action">
                <input type="hidden" name="_method" value="PUT" />
                <input type="submit" class="action inline visible" title="Видимость ответа" />
            </form>
            <?php endif; ?>

            <?php if ($this->permitions['deleting'] == 'on'): ?>
            <label for="preRemove-<?= $answer->id; ?>" class="action inline delete" title="Удалить вариант ответа"></label>
            <?php endif; ?>

        </div>

        <div class="short-row del-form hidden">
            <form action="<?= $rDeleteAnswer->getAbsolutePath(['id' => $answer->id]); ?>" name="interview-delete-form-<?= $answer->id ;?>" method="POST" class="quest">
                <input type="hidden" name="_method" value="DELETE" />
                <input type="submit" title="Удалить" class="inline yes icon" />
                <label for="preRemove-<?= $answer->id ;?>" title="Отмена" class="inline no icon"></label>
            </form>
        </div>

    </div>
</div>
