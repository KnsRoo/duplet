<section class="main-slider section">
  <div class="main-slider__container swiper-container">
    <div class="main-slider__wrapper swiper-wrapper">
      <?php foreach ($sliders as $key => $slider) : ?>
        <div class="main-slider__slide swiper-slide">
          <div class="main-slider__slide-wrapper wrapper">
            <h3 class="main-slider__title"><?= $slider->title ?></h3>
            <div class="main-slider__text"><?= $slider->announce ?></div>
            <div class="main-slider__social">
              <a class="main-slider__social-link link-social" href="<?= $social['vk'] ?>" aria-label="vkontakte">
                <svg class="icon">
                  <use href="icons/icons.svg#vk"></use>
                </svg>
              </a>
              <a class="main-slider__social-link link-social" href="<?= $social['instagram'] ?>" aria-label="instagram">
                <svg class="icon">
                  <use href="icons/icons.svg#instagram"></use>
                </svg>
              </a>
            </div>
          </div>
          <div class="main-slider__image" data-bg="url(<?= $slider->getPicture('800x800') ?>)">
            <div class="main-slider__image-overlay"></div>
            <div class="main-slider__bottom-panel">
              <div class="main-slider__button button">Перейти в каталог</div>
              <div class="main-slider__controls">
                <button class="main-slider__controls-btn icon-btn js-prev-btn" type="button" aria-label="Назад">
                  <svg class="icon">
                    <use href="icons/icons.svg#arr-left"></use>
                  </svg>
                </button>
                <div class="main-slider__controls-data"><?= sprintf("%02d", $key + 1) ?>/<?= sprintf("%02d", $countSlider) ?></div>
                <button class="main-slider__controls-btn icon-btn js-next-btn" type="button" aria-label="Вперед">
                  <svg class="icon">
                    <use href="icons/icons.svg#arr-right"></use>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</section>