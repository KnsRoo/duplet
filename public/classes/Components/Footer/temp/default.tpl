<footer class="footer">
  <div class="wrapper">
    <div class="footer__content">
      <?= $menu->getFooterHtml() ?>
      <div class="footer__contacts">
        <div class="footer__contacts_title">Контакты</div>
        <div class="footer__contacts_block">
          <div class="markets">
            <p class="markets__title">Магазины</p>
            <div class="markets__block">
              <ul class="markets__topic">
                <li>ул. Южная, 6</li>
                <li>ул. Гаражная, 27</li>
              </ul>
              <ul class="markets__topic">
                <li>8 (8212) 40-00-70</li>
                <li>8 (8212) 26-40-00</li>
              </ul>
            </div>
          </div>
          <div class="working-time">
            <p class="working-time__title">Режим работы</p>
            <div class="working-time__block">
              <ul class="working-time__topic">
                <li>Пн-Пт</li>
                <li>Сб-Вс</li>
              </ul>
              <ul class="working-time__topic">
                <li>с 10:00 до 19:00</li>
                <li>с 10:00 до 17:00</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="footer__social">
        <div class="footer__social_title">Социальные сети</div>
        <div class="footer__social_block">
          <div class="footer__social_topic"><a class="footer__social_link" href="#">
              <figure class="icon-vk"></figure>
            </a><a class="footer__social_link" href="#">
              <figure class="icon-inst"></figure>
            </a><a class="footer__social_link" href="#">
              <figure class="icon-fb"></figure>
            </a></div>
        </div>
      </div>
    </div>
    <p class="duplet__now">@ 2020 ООО “Дуплет”</p>
  </div>
</footer>