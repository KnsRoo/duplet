<?php 
use Websm\Framework\Router\Router;
?>
<main>
	<section class="welcome">
		<div class="welcome__catalog">
			<div class="welcome__title">
				<h1 class="main__title">ОРУЖЕЙНЫЙ МАГАЗИН “ДУПЛЕТ”</h1><a class="btn btn__to" href="/catalog"><span class="btn__name">в каталог</span></a>
			</div>
			<div class="welcome__image">
				<div class="example">
					<div class="example__block">
						<div class="example__block_title">Tedna Prime S12С</div>
						<div class="example__block_price">стоимость от 39990 руб</div>
					</div><a class="example__link" href="/catalog">
						<figure class="icon-add"></figure>
					</a>
				</div>
			</div>
		</div>
		<div class="welcome__laptop">
			<div class="welcome__laptop__image">
				<div class="welcome__laptop__title">
					<h1 class="main__title">ОРУЖЕЙНЫЙ МАГАЗИН “ДУПЛЕТ”</h1><a class="btn btn__to" href="/catalog"><span class="btn__name">в каталог</span></a>
				</div>
			</div>
			<div class="example__laptop">
				<div class="laptop__block">
					<div class="example__laptop__block">
						<div class="example__block_title">Tedna Prime S12С</div>
						<div class="example__block_price">стоимость от 39990 руб</div>
					</div><a class="example__laptop__link" href="/catalog">
						<figure class="icon-add"></figure>
					</a>
				</div>
				<div class="laptop__number">
					<?php foreach ($numbers as $value) : ?>
						<div class="laptop__number_title"><?= $value ?></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- CATEGORIES -->
	<div id = "categories" data-api-link = "<?= Router::byName('api:catalog:v3:base-groups')->getURL(); ?>"/>
	<!-- CATEGORIES -->

	<?php foreach ($sliders as $key => $value) : ?>
		<div id = "<?=$value->id?>" 
			 data-id = "<?=$key?>"
			 data-link = "<?=$value->link?>" 
			 data-title = "<?=$value->title?>" 
			 data-description = "<?= $value->description?>">
		</div>
	<?php endforeach ?>

	<section class="discount__block">
		<div class="wrapper">
			<div class="action__box">
				<div class="wrap">
					<div class="title">
						<h1 class="title__text">Акции и скидки</h1>
						<p class="title__description">Подпишитесь на нашу рассылку, чтобы первым получать
							уведомления о новых поступлениях, товарах, которые продаются по скидке, а также об
							акциях</p>
					</div>
					<div class="discount__item" style = "<?= 'background: '.$stocks[0]->getProp('цвет').';' ?>">
						<div class="discount__box">
							<div class="block__wrap">
								<div class="discount__box_title"><?= $stocks[0]->title ?></div>
								<div class="discount__box_text"><?= $stocks[0]->announce ?></div>
								<a href = "<?= Router::byName('catalog:product')->getURL(['productId' => $stocks[0]->getProp('Товар')[0]]) ?>" class="discount__box_link link">Купить сейчас</a>
							</div>
							<div class="img__wrap"><img class="discount__box_img" src="/assets/img/tent.png" alt="">
							</div>
						</div>
					</div>
				</div>
				<div class="wrap">
					<div class="discount__item" style = "<?= 'background: '.$stocks[1]->getProp('цвет').';' ?>">
						<div class="discount__box">
							<div class="block__wrap">
								<div class="discount__box_title"><?= $stocks[1]->title ?></div>
								<div class="discount__box_text"><?= $stocks[1]->announce ?></div>
								<a href = "<?= Router::byName('catalog:product')->getURL(['productId' => $stocks[1]->getProp('Товар')[0]]) ?>" class="discount__box_link link">Купить сейчас</a>
							</div>
							<div class="img__wrap"><img class="discount__box_img" src="/assets/img/kam.png" alt="">
							</div>
						</div>
					</div>
					<form class="email" action="" method="">
						<div class="email__title">
							<div class="email__title_main">Оставьте свой e-mail</div>
							<div class="email__title_notification">И получайте уведомления</div>
						</div>
						<div class="email__send">
							<div class="input"><input class="for__input" type="email" placeholder="Ваш e-mail" required>
								<div class="mistake__info">
									<figure class="icon-info"></figure>
									<div class="mistake__info_title">Ой, кажется такого e-mail не существует</div>
								</div>
							</div><a class="btn" href="#"><span class="btn__name">Отправить</span></a>
						</div>
					</form>
				</div>
			</div><a class="link__to link__center" href="/Stocks">Больше акций</a>
		</div>
	</section>
	<section class="tidings">
		<div class="wrapper">
			<div class="tidings__title">Новости</div>
			<div class="tidings__slider swiper-container">
				<div class="swiper-wrapper">
					<?php foreach ($news as $new) : ?>
					<div class="swiper-slide">
						<div class="news__block">
							<div class="img__item"><img class="img__news" src="<?= $new->picture ?>" alt=""></div>
							<div class="news__block_instruction">
								<div class="news__title"><?= $new->title ?></div>
								<div class="news__text"><?= $new->text ?></div>
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
			</div><a class="link__to more__news" href="#">Больше новостей</a>
		</div>
	</section>
</main>