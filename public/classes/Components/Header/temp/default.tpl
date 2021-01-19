<div id="modal-login"></div>
<header class="header">
  <div class="wrapper">
    <div class="header__flex">
      <div class="mobile__block">
        <div class="menu__btn"><span></span><span></span><span></span></div>
        <div class="search__box_mobile"><input class="input__search_mobile" placeholder="Введите для поиска ...">
          <div class="search__btn_mobile">
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
        <div class="search__box"><input class="input__search" placeholder="Введите для поиска ...">
          <div class="search__btn">
            <figure class="icon-search"></figure>
          </div>
          <div class="cancel__btn">
            <figure class="icon-menu-cancel"></figure>
          </div>
        </div>
        <div class="profile"><a class="icon-profile js-login-btn"></a><a class="icon-liked" href="/favorite">
            <div class="icon-liked_number">99</div>
          </a><a class="icon-basket" href="/cart">
            <div class="icon-basket_number">2</div>
          </a></div>
      </div>
    </div>
  </div>
</header>
<div class="header__popup">
  <div class="wrapper"><a class="private-office js-login-btn">
      <figure class="icon-profile"></figure>
      <div class="private-office__text">Личный кабинет</div>
    </a>
    <ul class="mobile__links">
      <li><a href="/catalog">Каталог</a></li>
      <li><a href="#">Акции</a></li>
      <li><a href="/news">Новости</a></li>
      <li><a href="/contacts">Контакты</a></li>
    </ul>
    <div class="contacts">
      <div class="contacts_number">8 (8212) 26-40-00</div>
      <div class="contacts_number">8 (8212) 40-00-70</div>
    </div>
  </div>
</div>