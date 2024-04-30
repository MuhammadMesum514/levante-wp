<?php if ( ! defined( 'ABSPATH' ) ) :
    exit; // Exit if accessed directly
endif;

    //---------------------------------------------------------------------------
    // Flickr widget
    //---------------------------------------------------------------------------

    class RT_Contact extends WP_Widget {

        public function __construct() {
            parent::__construct(
                'rt_contact_widget', // Base ID
                __('RT Contact Widget', 'homlisti-core'), // Name
                array('description' => esc_html__('Displays RT Contact Info', 'homlisti-core'),) // Args
            );
        }

        public function form($instance) {
          
            $defaults = array(
                'title'       => '',
                'address' => radiustheme\HomListi\RDTheme::$options['contact_address'],
                'mail' => radiustheme\HomListi\RDTheme::$options['contact_email'],
                'phone' => radiustheme\HomListi\RDTheme::$options['contact_phone'],
                'website' => radiustheme\HomListi\RDTheme::$options['contact_website'],
            );

            $instance = wp_parse_args( (array) $instance, $defaults ); ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title: ', 'homlisti-core'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                    name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                    value="<?php echo esc_attr($instance['title']); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('address'); ?>"><?php esc_html_e('Address: ', 'homlisti-core'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('address'); ?>"
                       name="<?php echo $this->get_field_name('address'); ?>" row="3"
                       value="<?php echo esc_attr($instance['address']); ?>"><?php echo esc_html($instance['address']); ?></textarea>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('mail'); ?>"><?php esc_html_e('Email: ', 'homlisti-core'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('mail'); ?>"
                       name="<?php echo $this->get_field_name('mail'); ?>" type="text"
                       value="<?php echo esc_attr($instance['mail']); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('phone'); ?>"><?php esc_html_e('Phone Number: ', 'homlisti-core'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>"
                       name="<?php echo $this->get_field_name('phone'); ?>" type="text"
                       value="<?php echo esc_attr($instance['phone']); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('website'); ?>"><?php esc_html_e('Website Address: ', 'homlisti-core'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('website'); ?>"
                       name="<?php echo $this->get_field_name('website'); ?>" type="text"
                       value="<?php echo esc_attr($instance['website']); ?>">
            </p>

           

        <?php }

        public function update($new_instance, $old_instance) {

            $instance = $old_instance;

            $instance[ 'title' ] = (!empty($new_instance[ 'title' ])) ? strip_tags($new_instance[ 'title' ]) : '';
            $instance[ 'address' ] = (!empty($new_instance[ 'address' ])) ? strip_tags($new_instance[ 'address' ]) : '';
            $instance[ 'mail' ] = (!empty($new_instance[ 'mail' ])) ? strip_tags($new_instance[ 'mail' ]) : '';
            $instance[ 'phone' ] = (!empty($new_instance[ 'phone' ])) ? strip_tags($new_instance[ 'phone' ]) : '';
            $instance[ 'website' ] = (!empty($new_instance[ 'website' ])) ? strip_tags($new_instance[ 'website' ]) : '';

            return $instance;
        }

        public function widget($args, $instance) {

            echo $args[ 'before_widget' ];
            $title = apply_filters('widget_title', $instance[ 'title' ]);
            if (!empty($title)) {
                echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
            }
            $_address = $instance[ 'address' ];
            $_mail = $instance[ 'mail' ];
            $_phone = $instance[ 'phone' ];
            $_website = $instance[ 'website' ];
            echo do_shortcode("[rt_contact address='{$_address}' mail='{$_mail}' phone='{$_phone}' website='{$_website}']");
            echo $args[ 'after_widget' ];
        }
    }


    // register widgets
    if (!function_exists('rt_contact_widget')) {
        function rt_contact_widget()
        {
            register_widget('RT_Contact');
        }

        add_action('widgets_init', 'rt_contact_widget');
    }