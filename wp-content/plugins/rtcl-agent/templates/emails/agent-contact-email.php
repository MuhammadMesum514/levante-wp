<?php
/**
 * Agent contact mail
 *
 * @package rtcl-agent/templates/emails
 * @version 1.0.0
 */


use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclEmail;


if (!defined('ABSPATH')) {
    exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
/** @var RtclEmail $email */
do_action('rtcl_email_header', $email); ?>
    <p><?php /** @var object $user */
        printf(__('Hi %s', 'rtcl-agent'), $user->display_name); ?>,</p>
    <p><?php printf(__('You have received a reply from your contact form at <strong>%s</strong>.', 'rtcl-agent'), Functions::get_blogname()); ?></p>
    <p><?php /** @var array $data */
	    printf(__('<strong>Name</strong> : %s', 'rtcl-agent'), $data['name']) ?></p>
    <p><?php printf(__('<strong>Email</strong> : %s', 'rtcl-agent'), $data['email']) ?></p>
    <p><?php printf(__('<strong>Phone</strong> : %s', 'rtcl-agent'), $data['phone']) ?></p>
    <p><?php printf(__('<strong>Message</strong> : %s', 'rtcl-agent'), nl2br($data['message'])) ?></p>
<?php
/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action('rtcl_email_footer', $email);
