<?php
/**
 * Adds Foo_Widget widget.
 */
class ImageBlock extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'image_block', // Base ID
			esc_html__( 'Текстовый блок с фоном', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'Текстовый блок во всю ширину с фоном картинкой', 'text_domain' ), ) // Args
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
		?>
		
		<div class="order-site" style="background-size: cover; <?php if ($instance['image_block_image_src']) { echo "background-image: url('{$instance['image_block_image_src']}');"; } ?> ">
		    <div class="container">
		      <div class="incontainer">
		      	<?php if ($instance['image_block_title']) { ?>
		        	<h1><?=$instance['image_block_title']?></h1>
		        <?php } ?>
		        <?php if ($instance['image_block_text']) { ?>
		        	<p><?=$instance['image_block_text']?></p>
		       	<?php } ?>
				
				<?php if ($instance['image_block_button_text']) { ?>
		        	<a href="<?=$instance['image_block_button_link']?>" class="btn btn__red"><?=$instance['image_block_button_text']?></a>
		        <?php } ?>

		      </div>
		    </div><!--container-->
		  </div>

		<?php
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

		$image_block_title = !empty($instance['image_block_title']) ? $instance['image_block_title'] : esc_html__('', 'text_domain');
		$image_block_text = !empty($instance['image_block_text']) ? $instance['image_block_text'] : esc_html__('', 'text_domain');

		$image_block_image_src = !empty($instance['image_block_image_src']) ? $instance['image_block_image_src'] : esc_html__('', 'text_domain');

		$image_block_button_text = !empty($instance['image_block_button_text']) ? $instance['image_block_button_text'] : esc_html__('', 'text_domain');
		$image_block_button_link = !empty($instance['image_block_button_link']) ? $instance['image_block_button_link'] : esc_html__('', 'text_domain');

		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_block_title' ) ); ?>"><?php esc_attr_e( 'Заголовок текста:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_block_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_block_title' ) ); ?>" type="text" value="<?php echo esc_attr( $image_block_title ); ?>"/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_block_text' ) ); ?>"><?php esc_attr_e( 'Текст:', 'text_domain' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_block_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_block_text' ) ); ?>"><?php echo esc_attr( $image_block_text ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_block_image_src' ) ); ?>"><?php esc_attr_e( 'Адрес фонового изображения:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_block_image_src' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_block_image_src' ) ); ?>" type="text" value="<?php echo esc_attr( $image_block_image_src ); ?>"/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_block_button_text' ) ); ?>"><?php esc_attr_e( 'Текст кнопки:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_block_button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_block_button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $image_block_button_text ); ?>"/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_block_button_link' ) ); ?>"><?php esc_attr_e( 'Ссылка кнопки:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_block_button_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_block_button_link' ) ); ?>" type="text" value="<?php echo esc_attr( $image_block_button_link ); ?>"/>
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

		$instance['image_block_title'] = ( ! empty( $new_instance['image_block_title'] ) ) ? strip_tags( $new_instance['image_block_title'] ) : '';
		$instance['image_block_text'] = ( ! empty( $new_instance['image_block_text'] ) ) ? strip_tags( $new_instance['image_block_text'] ) : '';

		$instance['image_block_image_src'] = ( ! empty( $new_instance['image_block_image_src'] ) ) ? strip_tags( $new_instance['image_block_image_src'] ) : '';

		$instance['image_block_button_text'] = ( ! empty( $new_instance['image_block_button_text'] ) ) ? strip_tags( $new_instance['image_block_button_text'] ) : '';
		$instance['image_block_button_link'] = ( ! empty( $new_instance['image_block_button_link'] ) ) ? strip_tags( $new_instance['image_block_button_link'] ) : '';
		

		return $instance;
	}

}

// register Foo_Widget widget
function register_imageblock_widget() {
    register_widget( 'ImageBlock' );
}
add_action( 'widgets_init', 'register_imageblock_widget' );