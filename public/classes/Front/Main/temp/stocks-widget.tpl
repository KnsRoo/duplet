<?php 
use Websm\Framework\Router\Router;
?>
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