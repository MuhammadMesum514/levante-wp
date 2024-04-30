<?php

use Rtcl\Helpers\Functions as RtclFunctions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'rtcl_agent_get_agent_info' ) ) {
	function rtcl_agent_get_agent_info( $post = null ) {
		$user_id   = get_post_meta( $post->ID, '_rtcl_user_id', true );
		if( $user_id ) {
		$store_id  = get_user_meta( $user_id, '_rtcl_store_id', true );
		$user_info = get_userdata( $user_id );
		$email     = $user_info->user_email;
		?>
        <div class="rtcl-agent-info-wrap">
            <ul>
                <li>
                    <strong><?php _e( "Email", 'rtcl-agent' ); ?>:</strong> <?php echo $email; ?>
                </li>
                <li>
                    <strong><?php _e( "Store", 'rtcl-agent' ) ?>:</strong> <?php echo get_the_title( $store_id ); ?>
                </li>
            </ul>
        </div>
		<?php
		}
	}
}

if ( ! function_exists( 'rtcl_agent_loop_start_class' ) ) {
	function rtcl_agent_loop_start_class( $classes = [] ) {
		$classes[] = 'rtcl-agents rtcl-grid-view';
		$classes[] = apply_filters( 'rtcl_agents_grid_columns_class', 'columns-4' );
		$classes   = array_map( 'esc_attr', array_unique( array_filter( $classes ) ) );
		$classes   = apply_filters( 'rtcl_agent_loop_start_class', $classes );
		if ( ! empty( $classes ) ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'rtcl_agent_loop_start' ) ) {
	function rtcl_agent_loop_start( $echo = true ) {
		RtclFunctions::set_loop_prop( 'loop', 0 );
		$loop_start = apply_filters( 'rtcl_agent_loop_start', RtclFunctions::get_template_html( 'agent/loop/loop-start', null, '', rtclAgent()->get_plugin_template_path() ) );

		if ( $echo ) {
			echo $loop_start; // WPCS: XSS ok.
		} else {
			return $loop_start;
		}
	}
}

if ( ! function_exists( 'rtcl_agent_loop_end' ) ) {
	function rtcl_agent_loop_end( $echo = true ) {

		$loop_end = apply_filters( 'rtcl_agent_loop_end', RtclFunctions::get_template_html( 'agent/loop/loop-end', null, '', rtclAgent()->get_plugin_template_path() ) );

		if ( $echo ) {
			echo $loop_end; // WPCS: XSS ok.
		} else {
			return $loop_end;
		}
	}
}

/**
 * Is it is Agent Archive page
 *
 * @return bool
 */
if ( ! function_exists( 'rtcl_is_agent' ) ) {
	function rtcl_is_agent() {
		return is_post_type_archive( rtclAgent()->post_type ) || ( ( $agent_page_id = RtclFunctions::get_page_id( 'agent' ) ) && is_page( $agent_page_id ) );
	}
}

/**
 * Is it Single Agent
 *
 * @return boolean
 */
if ( ! function_exists( 'rtcl_is_single_agent' ) ) {
	function rtcl_is_single_agent() {
		return is_singular( [ rtclAgent()->post_type ] );
	}
}


/**
 * Agent name
 *
 * @return string
 */
if ( ! function_exists( 'rtcl_get_agent_name' ) ) {
	function rtcl_get_agent_name( $user_id ) {
		$authorData = get_user_by('id', $user_id);
		$author_name = '';
		if (is_object($authorData)) {
			$author[] = $authorData->first_name;
			$author[] = $authorData->last_name;
			$author = array_filter($author);
			if (!empty($author)) {
				$author_name = implode(' ', $author);
			} else {
				$author_name = $authorData->display_name;
			}
		}

		return apply_filters('rtcl_store_get_agent_name', $author_name, $authorData);
	}
}