<?php
	get_header();
?>

<?php
	get_template_part('main_menu');
?>

	<?php
		while ( have_posts() ) {
			the_post();
			$the_meta = get_post_meta(get_the_ID());

			if ($the_meta['project_type'][0]) {
				$_type = get_page( $the_meta['project_type'][0] );
				$_type_parent = get_page( $_type->post_parent );

			}

			if ($the_meta['project_cms'][0]) {
				$_cms = get_page( $the_meta['project_cms'][0] );
				$_cms_parent = get_page( $_cms->post_parent );
			}

			/*$_post_categories = get_the_category(); //wp_get_post_categories( get_the_ID() );

			foreach ($_post_categories as $__cat) {
				if ($__cat->parent == 6) {
					$_post_site_type = $__cat;
				}
				elseif ($__cat->parent == 7) {
					$_post_cms_type = $__cat;
				}
			}*/

			$months = array(0 => '', 1 => "Январь", 2 => "Февраль", 3 => "Март", 4 => "Апрель", 5 => "Май", 6 => "Июнь", 7 => "Июль", 8 => "Август", 9 => "Сентябрь", 10 => "Октябрь", 11 => "Ноябрь", 12 => "Декабрь");


			/*echo "<pre>";
			print_r($the_meta);
			echo "</pre>";*/

			setlocale(LC_ALL, 'ru_RU.UTF-8');
	?>

  <div class="card-info">
    <div class="container">
      <div class="incontainer">
      	
		        <?=the_title('<h1>', '</h1>');?>
		        
		        <div class="card-info__content bg__grey">
		          <div>
		            <div class="title">Дата:</div>
		            <div class="description"><?=$months[date('n', get_the_time('U'))]?> <?=date("Y", get_the_time('U'))?></div>
		          </div>
		          <div>
		            <div class="title">Адрес сайта:</div>
		            <div class="description">
		              <a rel="nofollow" href="/link.php?go=<?=$the_meta['project_url'][0]?>"><?=$the_meta['project_url'][0]?></a>
		            </div>
		          </div>
		          <div>
		            <div class="title">Описание:</div>
		            <div class="description"><?=$the_meta['project_short_description'][0]?></div>
		          </div>
		          <?php
		          	if ($_type->post_title) {		          		
		          ?>
		          <div>
		            <div class="title">Тип сайта:</div>
		            <div class="description">
		              <a href="/<?=$_type_parent->post_name?>/<?=$_type->post_name?>/"><?=$_type->post_title?></a>
		            </div>
		          </div>
		          <?php
		          	}
		          	if ($_cms->post_title) {
		          ?>
		          <div>
		            <div class="title">Система управления:</div>
		            <div class="description">
		              <a href="/<?=$_cms_parent->post_name?>/<?=$_cms->post_name?>/"><?=$_cms->post_title?></a>
		            </div>
		          </div>
		          <?php
		          	}
		          ?>

		        </div>	

      </div>
    </div><!--container-->
  </div>


	<?php
		if (count($the_meta['project_picture_1']) || count($the_meta['project_picture_2'])) {
	?>
	  <div class="preview bg__grey-tile">
	    <div class="container">
	      <div class="incontainer">
	      	<?php
	      		if (!empty($the_meta['project_picture_1'][0])) {
	      	?>
		        <h2>Главная страница</h2>
				
		        <div class="preview__image">
		          <img src="<?=talanta_get_project_image($the_meta['project_picture_1'][0])?>" alt="main page"/>
		        </div>
	        <?php
	        	}
	        ?>

	        <?php
	      		if (!empty($the_meta['project_picture_2'][0])) {
	      	?>
		        <h2>Внутренняя страница</h2>

		        <div class="preview__image">
		          <img src="<?=talanta_get_project_image($the_meta['project_picture_2'][0])?>" alt="inner page"/>
		        </div>
	        <?php
	        	}
	        ?>

	        <?php
	      		if (!empty($the_meta['project_picture_3'][0])) {
	      	?>
		        <div class="preview__image">
		          <img src="<?=talanta_get_project_image($the_meta['project_picture_3'][0])?>" alt="inner page"/>
		        </div>
	        <?php
	        	}
	        ?>

	        <?php
	      		if (!empty($the_meta['project_picture_4'][0])) {
	      	?>
		        <div class="preview__image">
		          <img src="<?=talanta_get_project_image($the_meta['project_picture_4'][0])?>" alt="inner page"/>
		        </div>
	        <?php
	        	}
	        ?>

	      </div>
	    </div><!--container-->
	  </div>
	<?php
		}
	?>

 	<?php
		} // while have_posts() end
	?>

  <div class="question">
    <div class="container">
      <div class="incontainer">
        <form action="/" class="question__form">
          <h3>У Вас появились вопросы?</h3>
          <p>Напишите нам заявку и мы проконсультируем Вас по любым вопросам</p>

          <div class="form__box">
            <div>
              <div class="icon icon__user-red"></div>
              <input type="text" name="name" placeholder="Представьтесь" required="">
            </div>
            <div>
              <div class="icon icon__mobile-red"></div>
              <input type="text" name="phone" placeholder="Телефон" required="">
            </div>
            <div>
              <div class="icon icon__mail-red"></div>
              <input type="text" name="email" placeholder="Электронная почта" required="">
            </div>
          </div>

          <input class="btn btn__red" type="submit" value="Отправить">

        </form>

      </div>
    </div><!--container-->
  </div>
  
  <?php
    $_common_projects_text = new WP_Query( array( 'page_id' => 66 ) );
    
    $_common_projects_text->the_post();
    the_content();

    wp_reset_postdata();
    
  ?>

  <div class="content__block bg__grey-tile">
    <div class="container">
      <div class="incontainer">
        <h3>Смотрите также:</h3>
        
        <div class="view-more__links">
          <a href="#">Сайты на Hostcms</a>
          <span>|</span>
          <a href="#">Комплексная поддержка сайтов</a>
          <span>|</span>
          <a href="#">Проектирование</a>
        </div>

      </div>
    </div><!--container-->
  </div>


<?php
  get_footer();
?>