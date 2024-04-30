<?php

class RtclAgentMeta {

	public static function init() {
		add_action('add_meta_boxes', [__CLASS__, 'agent_meta_box']);
		add_action('save_post', [__CLASS__, 'save_agent_meta_data'], 10, 2);
	}

	public static function agent_meta_box() {
		add_meta_box(
			'rtcl_agent_information',
			esc_html__('Agent Information', 'rtcl-agent'),
			[__CLASS__, 'agent_information'],
			rtclAgent()->post_type,
			'normal',
			'high'
		);
	}

	static function agent_information($post) {
		$services = get_post_meta($post->ID, 'rtcl_agent_services', true);
		$specialties = get_post_meta($post->ID, 'rtcl_agent_specialties', true);
		?>
		<div class="rtcl-store-settings rtcl">
			<div class="form-group row">
				<label for="rtcl-agent-services" class="col-sm-3 control-label">
					<?php _e('Service Areas', 'rtcl-agent'); ?>
				</label>
				<div class="col-sm-9">
					<input type="text" name="services" id="rtcl-agent-services" value="<?php echo esc_attr($services); ?>" class="form-control"/>
				</div>
			</div>
            <div class="form-group row">
                <label for="rtcl-agent-specialties" class="col-sm-3 control-label">
					<?php _e('Specialties', 'rtcl-agent'); ?>
                </label>
                <div class="col-sm-9">
                    <textarea name="specialties" id="rtcl-agent-specialties" class="form-control"><?php echo esc_attr($specialties); ?></textarea>
                </div>
            </div>
		</div>
		<?php
	}

	static function save_agent_meta_data($post_id, $post) {

		if (!isset($_POST['post_type'])) {
			return $post_id;
		}

		if (rtclAgent()->post_type != $post->post_type) {
			return $post_id;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// Check the logged in user has permission to edit this post
		if (!current_user_can('manage_rtcl_options')) {
			return $post_id;
		}

		// Services
		if (isset($_POST['services'])) {
			$services = sanitize_text_field($_POST['services']);
			update_post_meta($post_id, 'rtcl_agent_services', $services);
		}

		// Specialties
		if (isset($_POST['specialties'])) {
			$specialties = sanitize_text_field($_POST['specialties']);
			update_post_meta($post_id, 'rtcl_agent_specialties', $specialties);
		}

		do_action('rtcl_agent_meta_data_saved', $post, $_REQUEST);

	}

}