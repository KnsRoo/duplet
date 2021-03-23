<mian>
	<section class="contacts">
		<div class="wrapper">
			<div class="title__page"><?= $page->title ?></div>
			<div class="contacts__text"><?= $page->announce ?></div>
			<div class="contacts__text"><?= $page->text ?></div>
			<div class="contacts__location">
				<div class="location__title">Наши магазины находятся по адресам:</div>
				<?php foreach ($shops as $shop) : ?>
				<div class="first__shop">
					<div class="location__info">
						<div class="info__title"><?= $shop->title ?></div>
						<div class="info__block">
							<div class="inner__block">
								<div class="street"><?= $shop->getProp('Адрес') ?></div>
								<div class="payment">
									<?php if ($shop->getProp('Наличный расчет')) : ?>
									<div class="payment__cash">Наличный расчет</div>
									<?php endif; ?>
									<?php if ($shop->getProp('Оплата по карте')) : ?>
									<div class="payment__card">Оплата по карте</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="inner__block">
								<div class="phone"><?= $shop->getProp('Телефон') ?></div>
								<div class="working__time">
									<?php 
									$arr = $shop->getProp('График работы');
									$keys = array_keys($arr); 
									?>
									<div class="day">
										<?php foreach ($keys as $key) : ?>
										<div class="day__title"><?=$key?></div>
										<?php endforeach; ?>
									</div>
									<div class="hours">
										<?php foreach ($keys as $key) : ?>
										<div class="hours__title"><?= $arr[$key]?></div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="location__slider swiper-container">
						<div class="swiper-wrapper">
							<?php
								$images = [];

								foreach ($shop->getProp('Изображения') as $value) {
									$file = \Model\FileMan\File::find(['id' => $value])
	                    						->get();
					                $protocol = 'http';

					                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
					                    $protocol = 'https';

					                $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

					                $prefix = $origin . \Back\Files\Config::PREFIX_PATH.'/';

					                if ($file->isPicture()) {
					                    $images[] = $prefix . $file->getPicture('1000x1000');
					                } else if($file->isVPicture()) {
					                    $images[] = $prefix . $file->getPicture('');
					                }
								}
							 ?>
							 <?php foreach ($images as $value) : ?> 
							 <div class="swiper-slide"><img class="location__img" src="<?= $value ?>" alt=""></div>
							<?php endforeach; ?>
						</div>
						<div class="swiper-pagination"></div>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
	</section>
</mian>