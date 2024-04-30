<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/new-post-notification-user.php
 *
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 * @var RtclEmail $email
 * @var Listing $listing
 * @var array $data
 */

use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
	<p><?php /* translators: User name */
		printf( esc_html__( 'Hi %s,', 'classified-listing' ), esc_html( $listing->get_owner_name() ) ); ?></p>
	<p><?php
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		printf(
		/* translators: Moderator email link */
			__( 'You have received this email from the moderator to improve your listing. Your listing is %s', 'classified-listing' ),
			sprintf( '<a href="%1$s">%2$s</a>',
				esc_url( $listing->get_the_permalink() ),
				esc_html( $listing->get_the_title() ) )
		) ?></p>
	<p><?php printf( '<strong>%s</strong>', esc_html__( 'Moderation:', 'classified-listing' ) ) ?></p>
	<p><?php echo wp_kses_post( wpautop( wptexturize( $data['message'] ) ) ); ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
