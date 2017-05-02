<?php
  get_header();
?>
  
  <?php
    get_template_part('main_menu');
  ?>

  <div class="page-header">
    <div class="overlay__bg"></div>
    <div class="container">
      <?php
        $_home_maintext = new WP_Query( array( 'page_id' => 17 ) );
        
        $_home_maintext->the_post();
        the_content();

        wp_reset_postdata();
        
      ?>
    </div><!--container-->
  </div>

  <div class="examples bg__grey-tile">
    <div class="container">
      <div class="incontainer">
        <h2>Наши работы</h2>
      </div>

      <div>
        <?php
          $_home_projects = new WP_Query( array( 'post_type' => 'projects', 'meta_key' => 'project_favorite', 'meta_value' => 1 ) );

          $_visible_limit = 9;
          $_start = 0;

          foreach ($_home_projects->posts as $_post) {
            $_start++;
            

            //$_home_projects->the_post();
            $_the_meta = get_post_meta($_post->ID);

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
                  <a target="_blank" rel="nofollow" href="<?=$_post_url?>" class="link__image"><img src="<?=get_the_post_thumbnail_url($_post->ID, 'original')?>" alt="Пример работы"></a>
                  <div class="examples__text" style="padding-left: 10px;">
                    
                    <div class="title" style="border-left: none;">
                      <a target="_blank" rel="nofollow" href="<?=$_post_url?>"><?=$_post->post_title?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        <?php    
            if ($_start >= $_visible_limit) {
              break;
            }        
          }
          //wp_reset_postdata();
        ?>        

        
          <?php
            $_hidden_start = 0;
            $_actually_showed = 0;
            foreach ($_home_projects->posts as $_post) {
              $_hidden_start++;

              if ($_hidden_start <= $_visible_limit) {
                continue;
              }

              $_actually_showed++;

              if ($_actually_showed == 1) {
                echo '<div class="hidden-examples" style="width: 100%;">';
              }

              //$_home_projects->the_post();
              $_the_meta = get_post_meta($_post->ID);

              //print_r($_the_meta);

              if ($_the_meta['project_direct_link'][0]) {
                $_post_url = "/link.php?go=" . $_the_meta['project_url'][0];
              }
              else {
                $_post_url = esc_url(get_permalink());
              }

          ?>
              <div class="examples__box" id="<?=$_actually_showed?>-<?php echo count($_home_projects->posts) . "-" . ($_actually_showed + $_visible_limit); ?>">
                <div class="incontainer">
                  <div class="examples__content">
                    <a target="_blank" rel="nofollow" href="<?=$_post_url?>" class="link__image"><img src="<?=get_the_post_thumbnail_url($_post->ID, 'original')?>" alt="Пример работы"></a>
                    <div class="examples__text">
                      <div class="data"><?=date("d.m.Y", get_the_time('U'))?></div>
                      <div class="title">
                        <a target="_blank" rel="nofollow" href="<?=$_post_url?>"><?=$_post->post_title?></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>              
          <?php    

              if ($_actually_showed % $_visible_limit == 0) {
                echo '</div>';
                if (count($_home_projects->posts) >= $_actually_showed + $_visible_limit) {
                  echo '<div class="hidden-examples" style="width: 100%;" >';
                }
              }
            }

            /*if ($_actually_showed % $_visible_limit == 0) {
              echo '</div>';
            }*/

          ?>          
        </div>
      </div>

      <button class="btn__more"
              data-role="slideDown"
              data-target=".hidden-examples">
        <span>Еще работы</span>
      </button>

    </div><!--container-->
  </div>

  <div class="info">
    <div class="container">
      <div class="about">
        <?php
          $_home_maintext = new WP_Query( array( 'page_id' => 83 ) );
          
          $_home_maintext->the_post();
          the_content();

          wp_reset_postdata();
          
        ?>
      </div>

      <?php
        $_news = new WP_Query( array('post_type' => 'news', 'posts_per_page' => 3) );
      ?>

      <div class="news">
        <div class="incontainer">
          <h2>Новости</h2>
          <?php
            if ($_news->have_posts()) {
              while ($_news->have_posts()) {
                $_news->the_post();
          ?>
                <p><?php the_content(); ?></p>
          <?php
              }
              wp_reset_postdata();
            }
          ?>
        </div>
      </div>

    </div><!--container-->
  </div>

<?php
  get_template_part('footer_form_text');

  get_footer();
?>