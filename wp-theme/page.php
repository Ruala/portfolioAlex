<?php
get_header();

get_template_part('main_menu');

dynamic_sidebar( 'home_main' );


if (have_posts()) {
	while (have_posts()) {
		the_post();
		the_content();
	}
}
else {
	echo "No such page";
}

$_page_id = get_the_ID();

if (get_the_ID() != 54) {
	get_template_part('footer_form_text');
}
 
get_footer();
?>