<section class="<?= $classCss ?> section">
  <div class="<?= $classCss ?>__wrapper wrapper">
    <div class="<?= $classCss ?>__header section__header">
      <h2 class="<?= $classCss ?>__title section__title"><?= $title ?></h2>
    </div>
    <div class="product-slider">
      <button class="product-slider__btn slider-btn left icon-btn js-prev-btn" type="button" aria-label="Назад">
        <svg class="icon">
          <use href="icons/icons.svg#arr-left"></use>
        </svg>
      </button>
      <button class="product-slider__btn slider-btn right icon-btn js-next-btn" type="button" aria-label="Вперед">
        <svg class="icon">
          <use href="icons/icons.svg#arr-right"></use>
        </svg>
      </button>
      <div class="product-slider__container swiper-container">
        <div class="product-slider__wrapper swiper-wrapper">
          <?php foreach ($products as $product) : ?>
            <div class="product-slider__slide swiper-slide">
              <div class="product-slider__item">
                <div class="product-slider__item-image swiper-lazy" data-background="<?= $product->getPicture('400x400') ?>">
                  <div class="product-slider__item-hover">
                    <a class="product-slider__item-hover-btn button" href="<?= $product->chpu ?>">Подробнее</a>
                    <div class="product-slider__item-hover-options">
                      <button class="button-round" type="button" aria-label="Нравится">
                        <svg class="icon">
                          <use href="icons/icons.svg#heart"> </use>
                        </svg>
                      </button>
                      <button class="button-round" type="button" aria-label="В корзину">
                        <svg class="icon">
                          <use href="icons/icons.svg#cart"> </use>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div><a class="product-slider__item-title" href="<?= $product->chpu ?>"><?= $product->title ?></a>
                <?php $eg = json_decode($product->props, true)['единица_измерения']['value'] ?? '' ?>
                <div class="product-slider__item-price"><?= round($product->price, 0) . '&nbsp;&#x20bd;' ?> <?= $eg ? '/ ' . $eg : '' ?> </div>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>
  </div>
</section>