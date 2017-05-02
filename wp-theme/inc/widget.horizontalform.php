<?php
/**
 * Adds Foo_Widget widget.
 */
class HorizontalForm extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'horizontal_form', // Base ID
			esc_html__( 'Горизонтальная форма (Запрос звонка)', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'Горизонтальная форма запроса звонка во всю ширину страницы', 'text_domain' ), ) // Args
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

		$_form_title = $instance['form_title'];
		$_form_description = $instance['form_description'];

		?>
		
		<div class="question bg__grey">
		    <div class="container">
		      <div class="incontainer">
		        <form action="/" class="question__form">
		          <?php
		          	if (empty($_form_title)) {
		          ?>
		          <h3>У Вас появились вопросы?</h3>
		          <?php
		          	}
		          	else {
		          		echo "<h3>{$_form_title}</h3>";
		          	}

		          	if (empty($_form_description)) {
		          ?>
		          <p>Напишите нам заявку и мы проконсультируем Вас по любым вопросам</p>
		          <?php
		          	}
		          	else {
		          		echo "<p>{$_form_description}</p>";
		          	}
		          ?>

		          <div class="form__box">
		            <div>
		              <div class="icon icon__user-red"></div>
		              <input type="text" name="callback_name" placeholder="Представьтесь" required="">
		            </div>
		            <div>
		              <div class="icon icon__mobile-red"></div>
		              <input type="text" name="callback_phone" placeholder="Телефон" required="">
		            </div>
		            <div>
		              <div class="icon icon__mail-red"></div>
		              <input type="text" name="callback_email" placeholder="Электронная почта" required="">
		            </div>
		          </div>
				  <input type="hidden" name="form_type" value="callback"/>
				  <input type="hidden" name="callback_from" value="<?=$_SERVER['REQUEST_URI']?>"/>
		          <input class="btn btn__red" type="submit" value="Отправить">

		        </form>

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

		if ( isset( $instance[ 'form_title' ] ) ) {
	        $form_title = $instance[ 'form_title' ];
	    }
	    else {
	        $form_title = ''; // __( 'Заголовок формы', 'wpb_widget_domain' );
	    }
	    //Repeat for option2
	    if ( isset( $instance[ 'form_description' ] ) ) {
	        $form_description = $instance[ 'form_description' ];
	    }
	    else {
	        $form_description = ''; // __( 'Описание формы', 'wpb_widget_domain' );
	    }

		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'form_title' ) ); ?>"><?php esc_attr_e( 'Заголовок формы:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_title' ) ); ?>" type="text" value="<?php echo esc_attr( $form_title ); ?>"/>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'form_description' ) ); ?>"><?php esc_attr_e( 'Описание формы:', 'text_domain' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_description' ) ); ?>"><?php echo esc_attr( $form_description ); ?></textarea>
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

		$instance['form_title'] = ( ! empty( $new_instance['form_title'] ) ) ? strip_tags( $new_instance['form_title'] ) : '';
		$instance['form_description'] = ( ! empty( $new_instance['form_description'] ) ) ? strip_tags( $new_instance['form_description'] ) : '';

		return $instance;
	}

}

// register Foo_Widget widget
function register_horizontalform_widget() {
    register_widget( 'HorizontalForm' );
}
add_action( 'widgets_init', 'register_horizontalform_widget' );