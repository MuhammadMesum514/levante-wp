<?php
/**
 * Agent contact form
 *
 * @author     RadiusTheme
 * @package    rtcl-agent/templates/agent
 * @version    1.0.0
 */
?>
<div id="agent-email-area" class="agent-email-area">
	<?php do_action( 'rtcl_agent_before_contact_form' ); ?>
    <h3><?php esc_html_e( "Contact With Me", "rtcl-agent" ); ?></h3>
    <form class="form" id="agent-email-form">
        <div class="form-group">
            <input type="text" name="name" id="sc-name"
                   placeholder="<?php esc_html_e( "Your name", "rtcl-agent" ); ?>"
                   class="form-control"
                   required>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <input type="email" name="email" id="sc-email"
                   placeholder="<?php esc_html_e( "Your email", "rtcl-agent" ); ?>"
                   class="form-control" required>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <input type="text" name="phone"
                   placeholder="<?php esc_html_e( "Phone number", "rtcl-agent" ); ?>"
                   id="sc-phone"
                   class="form-control">
            <div class="help-block"></div>
        </div>
        <div class="form-group">
                                <textarea rows="5" name="message" id="sc-message"
                                          placeholder="<?php esc_html_e( "Message", "rtcl-agent" ); ?>"
                                          class="form-control" required></textarea>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <div class="rtcl-g-recaptcha-wrap"></div>
        </div>
		<?php do_action( 'rtcl_agent_contact_form_captcha' ); ?>
        <button class="btn btn-primary sc-submit">
			<?php esc_html_e( "Send Message", "rtcl-agent" ); ?>
        </button>
        <div class="rtcl-response"></div>
    </form>
	<?php do_action( 'rtcl_agent_after_contact_form' ); ?>
</div>