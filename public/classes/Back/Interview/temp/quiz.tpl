<?php
use Websm\Framework\Router\Router;

$rUpdateVisQuiz = Router::byName('Interview.updateVisQuiz');
$rDeleteQuiz = Router::byName('Interview.deleteQuiz');
$rUpdateQuizSort = Router::byName('Interview.updateQuizSort');

?>
<div id="right-line-place-<?= $quiz->id ;?>">
    <input type="checkbox" class="hidden preRemove" id="preRemove-<?= $quiz->id ;?>" />
    <div class="line-box question relative box <?= $quiz->visible ? '' : 'unvis' ;?>" data-link="object-<?= $quiz->id ;?>">

        <div class="short-row title">
            <?= $quiz->title ? \Websm\Framework\StringF::cut($quiz->title, 10).' ...' : '---' ;?> <br />
        </div>

        <div class="short-row date"> <?= $quiz->getDate(); ?> </div>

        <div class="short-row sort">
            <form action="<?= $rUpdateQuizSort->getAbsolutePath(['id' => $quiz->id]); ?>" method="POST" >
                <input type="hidden" name="_method" value="PUT" />
                <input type="text" class="num" name="sort" value="<?= (int)$quiz->sort ;?>" title="Порядковый номер" 
                <?php if ($this->permitions['editing'] != 'on'): ?>
                disabled
                <?php endif; ?>
                />
            </form>
        </div>

        <div class="short-row actions">

            <?php if ($this->permitions['editing'] == 'on'): ?>
            <form action="<?= $rUpdateVisQuiz->getAbsolutePath(['id' => $quiz->id]); ?>" method="POST" class="inline action">
                <input type="hidden" name="_method" value="PUT" />
                <input type="submit" class="action inline visible" title="Видимость опроса" />
            </form>
            <?php endif; ?>

            <?php if ($this->permitions['deleting'] == 'on'): ?>
            <label for="preRemove-<?= $quiz->id; ?>" class="action inline delete" title="Удалить опрос"></label>
            <?php endif; ?>

        </div>

        <div class="short-row del-form hidden">
            <form action="<?= $rDeleteQuiz->getAbsolutePath(['id' => $quiz->id]); ?>" name="interview-delete-form-<?= $quiz->id ;?>" method="POST" class="quest">
                <input type="hidden" name="_method" value="DELETE" />
                <input type="submit" title="Удалить" class="inline yes icon" />
                <label for="preRemove-<?= $quiz->id ;?>" title="Отмена" class="inline no icon"></label>
            </form>
        </div>

        <a href="<?= $this->url.'/quiz-'.$quiz->id; ?>" class="absolute enter-link" title="Перейти в раздел"></a>

    </div>
</div>

