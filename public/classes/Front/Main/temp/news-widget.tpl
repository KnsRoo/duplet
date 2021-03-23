<section class="tidings">
	<div class="wrapper">
		<div class="tidings__title">Новости</div>
		<div class="tidings__slider swiper-container">
			<div class="swiper-wrapper">
				<?php foreach ($news as $new) : ?>
				<div class="swiper-slide">
					<div class="news__block">
						<div class="img__item"><img class="img__news" 
							src="<?= $new->picture ? $new->getPicture('1000x1000') : '/assets/img/default.png' ?>" alt=""></div>
						<div class="news__block_instruction">
							<div class="news__title"><?= $new->title ?></div>
							<div class="news__text"><?= $new->announce ?></div>
							<div class="news__card_read"><a class="btn" href="<?= $new->chpu ?>"><span class="btn__name">Читать</span></a><span class="news__data">
									<figure class="icon-data"></figure>
									<div class="news__data_title"><?= $new->getDate() ?></div>
								</span></div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="swiper-pagination"></div>
		</div><a class="link__to more__news" href="/news">Больше новостей</a>
	</div>
</section>