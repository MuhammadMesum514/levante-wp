<?php


namespace Rtcl\Emails;

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;

class ListingContactEmailToAdmin extends RtclEmail {

	public $data = [];

	function __construct() {

		$this->id            = 'listing_contact_admin';
		$this->template_html = 'emails/listing-contact-email-to-admin';

		// Call parent constructor.
		parent::__construct();
	}


	/**
	 * Get email subject.
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return __( '[{site_title}] Contact via - {listing_title}', 'classified-listing' );
	}

	/**
	 * Get email heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return esc_html__( 'An email is posted by user.', 'classified-listing' );
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param               $listing_id
	 * @param array      $data
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function trigger( $listing_id, $data = [] ) {

		$this->setup_locale();

		$this->data = $data;
		$listing    = null;
		if ( $listing_id ) {
			$listing = new Listing( $listing_id );
		}

		if ( is_a( $listing, Listing::class ) ) {
			$this->object = $listing;

			$this->placeholders = wp_parse_args( [ '{listing_title}' => $listing->get_the_title() ], $this->placeholders );
			$this->set_recipient( Functions::get_admin_email_id_s() );
		}

		if ( $this->get_recipient() ) {
			if ( ! empty( $this->data['name'] ) && ! empty( $this->data['email'] ) ) {
				$this->set_replay_to_name( $this->data['name'] );
				$this->set_replay_to_email_address( $this->data['email'] );
			}
			if ( isset( $this->data['attachment'] ) ) {
				$this->set_attachments( $this->data['attachment'] );
			}
			$this->send();
		}

		$this->restore_locale();
	}


	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return Functions::get_template_html(
			$this->template_html,
			[
				'listing' => $this->object,
				'email'   => $this,
				'data'    => $this->data
			]
		);
	}
}