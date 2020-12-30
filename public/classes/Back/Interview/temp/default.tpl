<?php

use Websm\Framework\Router\Router;

$this->css[] = 'css/interview.css';
$this->js[] = 'js/interview.js';
$this->js[] = 'plugins/ckeditor/ckeditor.js';

$rCreateQuiz = Router::byName('Interview.createQuiz');
$rCreateQuestion = Router::byName('Interview.createQuestion');
$rCreateAnswer = Router::byName('Interview.createAnswer');
$rGetQuestions = Router::byName('Interview.getQuestions');
$rGetAnswers = Router::byName('Interview.getAnswers');

?>

<div class="head-line relative">
    <div class="path inline">
        <a class="path-link" href="<?= $this->url; ?>" >Корень</a>
        <?php if ($quiz): ?>

        / <a class="path-link" href="<?= $rGetQuestions->getAbsolutePath(['id' => $quiz->id]); ?>" ><?= \Websm\Framework\StringF::cut($quiz->title, 10); ?></a>

        <?php endif; ?>

        <?php if ($question): ?>

        / <a class="path-link" href="<?= $rGetAnswers->getAbsolutePath(['cid' => $quiz->id, 'id' => $question->id]); ?>" ><?= \Websm\Framework\StringF::cut($question->title, 10); ?></a>

        <?php endif; ?>
    </div>
    <div class="main-title inline"><?= $this->title; ?></div>
</div>

<div class="data-content">
    <div class="module-data">

        <?php if ($this->permitions['creating'] == 'on' && $quiz && $question): ?>

        <div class="repeat">
                <form action="<?= $rCreateAnswer->getAbsolutePath(['cid' => $quiz->id, 'id' => $question->id]); ?>" method="POST" name="interview-form-new" class="add-new inline">
                <input type="text" value="" name="create[text]" placeholder="Название нового ответа" class="text-row inline anim text-focus" />
                <input type="submit" class="goodBtn inline" value="Добавить" />
            </form>
        </div>

        <?php elseif ($this->permitions['creating'] == 'on' && $quiz): ?>

        <div class="repeat">
                <form action="<?= $rCreateQuestion->getAbsolutePath(['id' => $quiz->id]); ?>" method="POST" name="interview-form-new" class="add-new inline">
                <input type="text" value="" name="create[title]" placeholder="Название нового вопроса" class="text-row inline anim text-focus" />
                <input type="submit" class="goodBtn inline" value="Добавить" />
            </form>
        </div>

        <?php elseif ($this->permitions['creating'] == 'on'): ?>

        <div class="repeat">
            <form action="<?= $rCreateQuiz->getAbsolutePath() ;?>" method="POST" name="interview-form-new" class="add-new inline">
                <input type="text" value="" name="create[title]" placeholder="Название нового опроса" class="text-row inline anim text-focus" />
                <input type="submit" class="goodBtn inline" value="Добавить" />
            </form>
        </div>

        <?php endif; ?>

        <?= $this->content; ?>

    </div>
</div>
