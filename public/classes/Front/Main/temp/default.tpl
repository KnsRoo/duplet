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
	<div id = "categories" data-api-link = "<?= Router::byName('api:catalog:v3:base-groups')->getURL(); ?>"></div>
	<!-- CATEGORIES -->

	<?= $slidersWidget ?>

	<?= $stocksWidget ?>

	<?= $newsWidget ?>
</main>