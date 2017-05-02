<?php
	get_header();
?>

<?php
  get_template_part('main_menu');

  $_current_category = get_query_var('cat');

  if ($_current_category == 5) {
    
  }
  else {

?> 
  <div class="container">
    <div style="padding: 50px 0 100px 0;">
      <?php        
        $news_years = get_meta_values('news_year', 'news');        
      ?>
      <div class="news-header">
        <h2>Новости</h2>
      </div>
      <div class="news-floating news-years">
        <ul class="nosquares">
          <?php
            foreach ($news_years as $year) {
              if (isset($wp_query->query_vars['year']) && $wp_query->query_vars['year'] == $year) {
                $class = "current";
              }
              else {
                $class = "";
              }

              echo "<li><a href='/news/{$year}/' class='year-link {$class}'><span>{$year}</span></a></li>";

            }
          ?>
          <li><a href='/news/' class='year-link <?php if (!isset($wp_query->query_vars['year']) || empty($wp_query->query_vars['year'])) { echo "current"; } ?>'><span>Все</span></a></li>
        </ul>
      </div>
      <div class="news-floating news-container">    
        <ul>
          <?php

            if (isset($wp_query->query_vars['year']) && !empty($wp_query->query_vars['year'])) {
              $args = array(
                'post_type' => 'news',
                'meta_key' => 'news_year',
                'meta_value' => (int)$wp_query->query_vars['year']
              );
            }
            else {
              $args = array( 'post_type' => 'news' );
            }


            $all_news = new WP_Query( $args );

            if ($all_news->have_posts()) {
              while ($all_news->have_posts()) {
                $all_news->the_post();
              ?>
                <li>
                  <p><?php the_content(); ?></p>
                </li>
              <?php              
              }
              wp_reset_postdata();
            }
          ?>          
        </ul>
      </div>
      <div style="clear: both;"></div>
    </div>
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