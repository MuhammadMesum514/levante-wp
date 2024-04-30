<?php if ( ! defined( 'ABSPATH' ) ) :
	exit; // Exit if accessed directly
endif;

//---------------------------------------------------------------------------
// Flickr widget
//---------------------------------------------------------------------------

class RT_Mortgage_Calculator extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'rt_mortgage_calculator', // Base ID
			__('RT Mortgage Calculator', 'homlisti-core'), // Name
			array('description' => esc_html__('Displays RT Mortgage Calculator', 'homlisti-core'),) // Args
		);
	}

	public function form($instance) {

		$defaults = array(
			'title'       => '',
			'subtitle'       => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title: ', 'homlisti-core'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
			       name="<?php echo $this->get_field_name('title'); ?>" type="text"
			       value="<?php echo esc_attr($instance['title']); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php esc_html_e('Subtitle: ', 'homlisti-core'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>"
			          name="<?php echo $this->get_field_name('subtitle'); ?>" row="3"
			          value="<?php echo esc_attr($instance['subtitle']); ?>"><?php echo esc_html($instance['subtitle']); ?></textarea>
		</p>

	<?php }

	public function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance[ 'title' ] = (!empty($new_instance[ 'title' ])) ? strip_tags($new_instance[ 'title' ]) : '';
		$instance[ 'subtitle' ] = (!empty($new_instance[ 'subtitle' ])) ? strip_tags($new_instance[ 'subtitle' ]) : '';

		return $instance;
	}

	public function widget($args, $instance) {
		echo $args[ 'before_widget' ];
		$title = apply_filters('widget_title', $instance[ 'title' ]);
		if (!empty($title)) {
			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		}
		$_subtitle = $instance[ 'subtitle' ];
		echo do_shortcode("[rtcl_emi_calculator subtitle='{$_subtitle}']");
		echo $args[ 'after_widget' ];
	}
}


// register widgets
if (!function_exists('rt_mortgage_calculator')) {
	function rt_mortgage_calculator()
	{
		register_widget('RT_Mortgage_Calculator');
	}

	add_action('widgets_init', 'rt_mortgage_calculator');
}