<?php
use Websm\Framework\Router\Router;

$rUpdateVisQuestion = Router::byName('Interview.updateVisQuestion');
$rDeleteQuestion = Router::byName('Interview.deleteQuestion');
$rUpdateQuestionSort = Router::byName('Interview.updateQuestionSort');

?>
<div id="right-line-place-<?= $question->id ;?>">
    <input type="checkbox" class="hidden preRemove" id="preRemove-<?= $question->id ;?>" />
    <div class="line-box question relative box <?= $question->visible ? '' : 'unvis' ;?>" data-link="object-<?= $question->id ;?>">

        <div class="short-row title">
            <?= $question->title ? \Websm\Framework\StringF::cut($question->title, 10).' ...' : '---' ;?> <br />
        Кол-во голосов: <?= $question->getTotalVotes(); ?>
        </div>

        <div class="short-row date"> <?= $question->getDate(); ?> </div>

        <div class="short-row sort">
            <form action="<?= $rUpdateQuestionSort->getAbsolutePath(['id' => $question->id]); ?>" method="POST" >
                <input type="hidden" name="_method" value="PUT" />
                <input type="text" class="num" name="sort" value="<?= (int)$question->sort ;?>" title="Порядковый номер" 
                <?php if ($this->permitions['editing'] != 'on'): ?>
                disabled
                <?php endif; ?>
                />
            </form>
        </div>

        <div class="short-row actions">

            <?php if ($this->permitions['editing'] == 'on'): ?>
            <form action="<?= $rUpdateVisQuestion->getAbsolutePath(['id' => $question->id]); ?>" method="POST" class="inline action">
                <input type="hidden" name="_method" value="PUT" />
                <input type="submit" class="action inline visible" title="Видимость опроса" />
            </form>
            <?php endif; ?>

            <?php if ($this->permitions['deleting'] == 'on'): ?>
            <label for="preRemove-<?= $question->id; ?>" class="action inline delete" title="Удалить опрос"></label>
            <?php endif; ?>

        </div>

        <div class="short-row del-form hidden">
            <form action="<?= $rDeleteQuestion->getAbsolutePath(['id' => $question->id]); ?>" name="interview-delete-form-<?= $question->id ;?>" method="POST" class="quest">
                <input type="hidden" name="_method" value="DELETE" />
                <input type="submit" title="Удалить" class="inline yes icon" />
                <label for="preRemove-<?= $question->id ;?>" title="Отмена" class="inline no icon"></label>
            </form>
        </div>

        <a href="<?= $this->url.'/quiz-'.$question->quiz_id.'/question-'.$question->id; ?>" class="absolute enter-link" title="Перейти в раздел"></a>

    </div>
</div>

