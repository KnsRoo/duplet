<main>
 <section class="news__item">
    <div class="wrapper">
       <h1 class="item__title"><?=$page->title?></h1>
       <div>
        <?= $page->announce ?>
       </div>
       <br>
       <div>
        <?= $page->text ?>
       </div>
       <div class="info__block">
          <div class="date__news">
             <p class="date__news_title"><?= $page->getDate() ?></p>
          </div>
          <a class="next__news" href="#"><img class="next__news_img" src="img/next__news.png" alt="Следующая новость"><span class="next__news_title">Следующая новость</span></a>
       </div>
    </div>
 </section>
</main>
