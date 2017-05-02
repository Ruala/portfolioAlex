<?php
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	set_post_thumbnail_size( 360, 214 );

	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	function arphabet_widgets_init() {

		register_sidebar( array(
			'name'          => 'Место под телефон в шапке',
			'id'            => 'header_phone',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		) );

		register_sidebar( array(
			'name'          => 'Текст в подвале',
			'id'            => 'footer_text',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		) );

	}
	add_action( 'widgets_init', 'arphabet_widgets_init' );


	if (file_exists(dirname(__FILE__) . '/inc/widget.horizontalform.php')) {
		include_once dirname(__FILE__) . '/inc/widget.horizontalform.php';
	}

	if (file_exists(dirname(__FILE__) . '/inc/widget.portfolio.php')) {
		include_once dirname(__FILE__) . '/inc/widget.portfolio.php';
	}

	if (file_exists(dirname(__FILE__) . '/inc/widget.imageblock.php')) {
		include_once dirname(__FILE__) . '/inc/widget.imageblock.php';
	}


	function talanta_get_project_image( $id, $size = 'original') {
		$tmp = wp_get_attachment_image_src($id, $size);

		return $tmp[0];
	}

	function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {

	    global $wpdb;

	    if( empty( $key ) )
	        return;

	    $r = $wpdb->get_col( $wpdb->prepare( "
	        SELECT pm.meta_value FROM {$wpdb->postmeta} pm
	        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
	        WHERE pm.meta_key = '%s' 
	        AND p.post_status = '%s' 
	        AND p.post_type = '%s'
	    ", $key, $status, $type ) );

	    return $r;
	}

	/*function add_rewrite_rules($aRules) {
		$aNewRules = array('news/([^/]+)/?$' => 'index.php?pagename=archive-news&year=$matches[1]');
		$aRules = $aNewRules + $aRules;
		print_r($aRules);
		return $aRules;
	}
	 
	// hook add_rewrite_rules function into rewrite_rules_array
	add_filter('rewrite_rules_array', 'add_rewrite_rules');*/

	add_rewrite_rule('^news/([0-9]+)/?', 'index.php?post_type=news&year=$matches[1]', 'top');
?>