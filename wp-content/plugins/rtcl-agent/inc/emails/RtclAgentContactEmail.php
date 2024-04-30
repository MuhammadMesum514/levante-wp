<?php

use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclEmail;

class RtclAgentContactEmail extends RtclEmail {

	protected $data = array();

	function __construct() {
		$this->id            = 'agent_contact_email';
		$this->template_html = 'emails/agent-contact-email';

		// Call parent constructor.
		parent::__construct();
	}


	/**
	 * Get email subject.
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return __( '[{site_title}] Contact via : Agent', 'rtcl-agent' );
	}

	/**
	 * Get email heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Agent contact mail', 'rtcl-agent' );
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param          $user_id
	 * @param array $data
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function trigger( $user_id, $data = array() ) {
		$return = false;
		if ( ! $user_id || ! isset( $data['user_id'] ) ) {
			return false;
		}
		$user = get_user_by('id', $user_id);
		$this->data = $data;
		$this->setup_locale();
		$this->object = $user;

		$this->set_recipient( $user->user_email );

		if ( $this->get_recipient() ) {
			if ( ! empty( $this->data['name'] ) && ! empty( $this->data['email'] ) ) {
				$this->set_replay_to_name( $this->data['name'] );
				$this->set_replay_to_email_address( $this->data['email'] );
			}
			$return = $this->send();
		}

		$this->restore_locale();

		return $return;
	}


	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return Functions::get_template_html(
			$this->template_html, array(
			'user'  => $this->object,
			'data'  => $this->data,
			'email' => $this,
		), '', rtclAgent()->get_plugin_template_path()
		);
	}

}
