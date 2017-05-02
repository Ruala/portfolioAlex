<?php
	get_header();
?>

<?php
  get_template_part('main_menu');

  $_current_category = get_query_var('cat');

  if ($_current_category == 5) {
    //echo "<h1>We in HostCMS</h1>";
  }
  else {

?>

  <div class="order-site bg__pink-glass">
    <div class="container">
      <div class="incontainer">
        <h1>Профессиональная разработка сайта</h1>
        <p>Сформировать клиентскую базу компании можно с помощью удачного сайта. Таким образом, будет осуществляться взаимосвязь с существующими и потенциальными клиентами. Ресурс, состоящий из нескольких страниц и отображающий интересы владельца, принято называть визиткой. Чаще такие сайты вызывают интерес частных предпринимателей, которым необходимо составить краткое представление о себе.</p>

        <a href="#" class="btn btn__red">Заказать сайт</a>

      </div>
    </div><!--container-->
  </div>

  <div class="examples bg__grey-tile">
    <div class="container">
      <div class="incontainer">
        <h2>Примеры работ</h2>
      </div>

      <div>
      	<?php

      	  $_query = new WP_Query( array( 'post_type' => 'projects', 'cat' => get_query_var('cat') ) );      	  

      	  if ($_query->have_posts()) {
          	while ($_query->have_posts()) {
	            $_query->the_post();
              $_the_meta = get_post_meta(get_the_ID());

              //print_r($_the_meta);

              if ($_the_meta['project_direct_link'][0]) {
                $_post_url = "/link.php?go=" . $_the_meta['project_url'][0];
              }
              else {
                $_post_url = esc_url(get_permalink());
              }
        ?>
	            <div class="examples__box">
	              <div class="incontainer">
	                <div class="examples__content">
	                  <a target="_blank" rel="nofollow" href="<?=$_post_url?>" class="link__image"><img src="<?=get_the_post_thumbnail_url(null, 'original')?>" alt="Пример работы"></a>
	                  <div class="examples__text">
	                    <div class="data"><?=date("d.m.Y", get_the_time('U'))?></div>
	                    <div class="title">
	                      <a target="_blank" rel="nofollow" href="<?=$_post_url?>"><?=the_title();?></a>
	                    </div>
	                  </div>
	                </div>
	              </div>
	            </div>
        <?php            
          	}
          }
          else {
          	echo "<p style='font-size: 22px; font-family: Georgia; font-style: italic; color: #333; text-align: center; margin: 50px 0;'>Здесь пока нет проектов</p>";
          }          
          wp_reset_postdata();
        ?>          

      </div>

    </div><!--container-->
  </div>

  <footer>
    <div class="container">
      <form action="/" class="form footer__form" method="post">
        <div class="incontainer">
          <h3>Оставьте заявку на расчет стоимости</h3>
        </div>

        <div class="form__box">
          <div class="incontainer">
            <input type="text" name="name" placeholder="Имя">
            <textarea name="description" placeholder="Опишите ваш проект"></textarea>
          </div>
        </div>

        <div class="form__box">
          <div class="incontainer">
            <input type="text" name="phone" placeholder="Телефон" required>
            <input type="text" name="email" placeholder="Электронная почта" required>
            <label for="file_1" class="label__file"><span class="icon__addfile"></span>Прикрепить файл</label>
            <input id="file_1" class="hidden" type="file" name="file">
            <input class="btn__submit" type="submit" value="Рассчитать">
          </div>
        </div>

      </form>

      <div class="footer__contacts">
        <div class="incontainer">
          <h3>Контактная информация</h3>
          <div class="contact-us">
            <div class="icon icon__mobile">+7 (495) 555-56-56</div>
            <div class="icon icon__mail">privet@talatnta.ru</div>
            <div class="icon icon__point">Москва, Космонавта Волкова 10</div>
          </div>

          <div class="social">
            <p><b>Мы в социальных сетях:</b></p>
            <a href="#" class="icon__facebook"></a>
            <a href="#" class="icon__twitter"></a>
            <a href="#" class="icon__instagram"></a>
            <a href="#" class="icon__flickr"></a>
          </div>
        </div>
      </div>
    </div><!--container-->
  </footer>

<?php
  
  } // else end


	get_footer();
?>