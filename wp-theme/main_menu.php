<?php

?>
<div class="page-wrap">
  <header>
    <div class="container">
      <div class="incontainer">
        <div class="logo">
          <a href="/"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/logo.png" alt="logo"></a>
        </div>
		<?php
			$header_menu = wp_get_nav_menu_items( 'header_menu' );
		?>
        <div class="header__menu__wrap">
          <ul class="header__menu">
          	<?php
          		foreach ($header_menu as $menu_item) {
          			echo "<li><a href='{$menu_item->url}'>{$menu_item->title}</a></li>";
          		}
          	?>
          </ul>
        </div>
        <nav id="m-menu-btn-wrapper" class="mobile-nav Fixed">
          <div id="hamburger" class="menu-button hamburger hamburger--squeeze js-hamburger">
            <div class="hamburger-box">
              <div class="hamburger-inner"></div>
            </div>
          </div>
        </nav>
        <nav id="m-menu"> <!-- mobile menu -->
          <ol>
            <?php
          		foreach ($header_menu as $menu_item) {
          			echo "<li><a href='{$menu_item->url}'>{$menu_item->title}</a></li>";
          		}
          	?>
          </ol>
        </nav><!-- mobile menu -->

        <div class="header__contacts">
          <a href="#call-back" class="btn__callback" data-role="lightbox">Обратный звонок</a>
          <div class="header__phone"><?php dynamic_sidebar( 'header_phone' ); ?></div>
        </div>

      </div>
    </div><!--container-->
  </header>