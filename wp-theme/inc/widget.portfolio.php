<?php

class PortfolioWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'portfolio_widget', // Base ID
			esc_html__( 'Примеры работ', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'Блок с 6 последними проектами из портфолио во всю ширину страницы', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		$queried_object = get_queried_object();
 		$_current_page_id = (int)$queried_object->ID;

 		//$_current_title = get_the_title( $_current_page_id );

 		$my_wp_query = new WP_Query();
		$all_wp_pages = $my_wp_query->query(array('post_type' => 'page', 'posts_per_page' => '-1'));

		$_project_types = get_page_by_title('Разработка');
		$_project_cms = get_page_by_title('Системы управления');		

		// Filter through all pages and find Portfolio's children
		//$types_children = get_page_children( $_project_types->ID, $all_wp_pages );
		//$cms_children = get_page_children( $_project_cms->ID, $all_wp_pages );

		$__type = array();
		$__cms = array();

		foreach ($all_wp_pages as $wp_page) {
			if ($wp_page->post_parent == $_project_types->ID) {
				$__type[] = $wp_page->ID;
			}
			elseif ($wp_page->post_parent == $_project_cms->ID) {
				$__cms[] = $wp_page->ID;
			}
		}

 		if (in_array($_current_page_id, $__type)) {
 			$query_args = array(
				"post_type" => "projects",
				"posts_per_page" => 6,
				"orderby" => 'date',
				"order" => 'DESC',
				"meta_key" => 'project_type',
				"meta_value" => $_current_page_id
			);
 		}
 		elseif (in_array($_current_page_id, $__cms)) {
 			$query_args = array(
				"post_type" => "projects",
				"posts_per_page" => 6,
				"orderby" => 'date',
				"order" => 'DESC',
				"meta_key" => 'project_cms',
				"meta_value" => $_current_page_id
			);
 		}		
 		else {	
			$query_args = array(
				"post_type" => "projects",
				"posts_per_page" => 6,
				"orderby" => 'date',
				"order" => 'DESC'
			);
		}

		$get_projects = new WP_Query( $query_args );

		$_loop_array = $get_projects->posts;

		if (count($get_projects->posts) < 6) {
			//echo "We dont have enough projects to show... (" . count($get_projects->posts) . ")<br>";
			$universal_query = array(
				"post_type" => "projects",
				"posts_per_page" => 6 - count($get_projects->posts),
				"orderby" => 'date',
				"order" => 'DESC',
				"meta_key" => 'project_universal',
				"meta_value" => 1
			);
			$get_universal_projects = new WP_Query( $universal_query );

			//echo "We found additional ".count($get_universal_projects->posts)." universal posts!<br>";

			$_loop_array = $_loop_array + $get_universal_projects->posts;

			//echo "Now total posts are: " . count($_loop_array) . "<br>";
		}
		else {
			//echo "We have enough posts as it is!<br>";
		}

		?>
		<div class="examples bg__grey-tile">
		    <div class="container">
		      <div class="incontainer">
		        <h2>Примеры работ</h2>
		      </div>

		      <div>
		<?php      
		if (count($_loop_array)) {
			foreach ($_loop_array as $_looped) {
				//$get_projects->the_post();
				$_the_meta = get_post_meta($_looped->ID);

	            //print_r($_the_meta);

	            if ($_the_meta['project_direct_link'][0]) {
	              $_post_url = "/link.php?go=" . $_the_meta['project_url'][0];
	            }
	            else {
	              $_post_url = esc_url('/projects/' . $_looped->post_name);
	            }
		?>
			
				<div class="examples__box">
		          <div class="incontainer">
		            <div class="examples__content">
		              <a target="_blank" rel="nofollow" href="<?=$_post_url?>" class="link__image"><img src="<?=get_the_post_thumbnail_url($_looped->ID, 'post-thumbnail')?>" alt="<?=$_looped->post_name?>"></a>
		              <div class="examples__text">
		                <div class="data"><?=date("d.m.Y", get_the_time('U'))?></div>
		                <div class="title">
		                  <a target="_blank" rel="nofollow" href="<?=$_post_url?>"><?=$_looped->post_title?></a>
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
		?>
			  </div>

	    	</div><!--container-->
	  	</div>
	  	<?php

		wp_reset_postdata();


		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

}

// register Foo_Widget widget
function register_portfolio_widget() {
    register_widget( 'PortfolioWidget' );
}
add_action( 'widgets_init', 'register_portfolio_widget' );