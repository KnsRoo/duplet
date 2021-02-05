<div id="modal-login"></div>
<header class="header">
  <div class="wrapper">
    <div class="header__popup">
      <div class="wrapper"><a class="private-office" href="/lk">
          <figure class="icon-profile"></figure>
          <div class="private-office__text">Личный кабинет</div>
        </a>
        <ul class="items__link">
          <li class="item"><a class="catalog_link" href="/catalog">Каталог</a></li>
          <li class="item"><a class="action_link" href="/404">Акции</a></li>
          <li class="item"><a class="news_link" href="/news">Новости</a></li>
          <li class="item"><a class="contacts_link" href="/contacts">Контакты</a></li>
        </ul>
        <div class="contacts">
          <div class="contacts_number">8 (8212) 26-40-00</div>
          <div class="contacts_number">8 (8212) 40-00-70</div>
        </div>
      </div>
    </div>
    <div class="header__flex">
      <div class="mobile__block">
        <div class="menu__btn"><span></span><span></span><span></span></div>
        <div class="search__box_mobile"><input class="input__search_mobile js-query-m" placeholder="Введите для поиска ...">
          <div class="search__btn_mobile js-search">
            <figure class="icon-search"></figure>
          </div>
          <div class="cancel__btn_mobile">
            <figure class="icon-menu-cancel"></figure>
          </div>
        </div>
      </div>
      <div class="block__first"><a class="logo" href="/"><img class="logo__img" src="/assets/img/logo.svg" alt="Логотип"></a>
        <?= $menu ?>
      </div>
      <div class="block__second">
        <div class="phone">
          <?php foreach ($options["Телефоны"] as $phone) : ?>
            <div class="phone__number"><?= $phone ?></div>
          <?php endforeach ?>
        </div>
        <div class="search__box"><input class="input__search js-query" placeholder="Введите для поиска ...">
          <div class="search__btn js-search-m">
            <figure class="icon-search"></figure>
          </div>
          <div class="cancel__btn">
            <figure class="icon-menu-cancel"></figure>
          </div>
        </div>
        <div id="profile"></div>
      </div>
    </div>
  </div>
</header>