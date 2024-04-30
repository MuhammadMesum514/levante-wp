<?php

namespace radiustheme\HomListi_Core;

class Shortcodes {

	protected static $instance = null;

	public function __construct() {
		add_shortcode( 'rtcl_emi_calculator', [ $this, 'EMI_Calculator' ] );
		add_shortcode( 'rt_contact', [ $this, 'rt_contact_render' ] );
		add_action( 'wp_footer', [ $this, 'rt_emi_calculator' ], 1000 );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function rt_contact_render( $atts, $content ) {
		extract( shortcode_atts( [
			'address' => '121 King St, Melbourne den 3000, Australia',
			'mail'    => 'info@example.com',
			'phone'   => '(+123) 596 000',
			'website' => '',
		], $atts ) );

		ob_start();
		?>
        <div class="rt-contact-wrapper">
            <ul>
				<?php if ( $address ) : ?>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <p><?php echo esc_html( $address ); ?></p>
                    </li>
				<?php endif; ?>

				<?php if ( $mail ) : ?>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <p><a target="_blank" href="mailto:<?php echo esc_html( $mail ); ?>"><?php echo esc_html( $mail ); ?></a></p>
                    </li>
				<?php endif; ?>

				<?php if ( $phone ) : ?>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <p><a target="_blank" href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></p>
                    </li>
				<?php endif; ?>

				<?php if ( $website ) : ?>
                    <li>
                        <i class="fas fa-globe"></i>
                        <p><a target="_blank" href="<?php echo esc_url( $website ); ?>"><?php echo esc_html( $website ); ?></a></p>
                    </li>
				<?php endif; ?>
            </ul>
        </div>
		<?php
		return ob_get_clean();
	}

	public function EMI_Calculator( $atts ) {
		$atts = shortcode_atts(
			[
				'buttonText'  => __( 'Calculate', 'homlisti-core' ),
				'buttonClass' => 'submit-btn',
				'subtitle'    => '',
			], $atts, 'rtcl_emi_calculator'
		);

		ob_start();

		?>
        <div id="mortgage-calculator" class="mortgage-calculator">
			<?php
			if ( $atts['subtitle'] ) :
				echo '<div class="subtitle">' . $atts['subtitle'] . '</div>';
			endif;
			?>
            <form action="<?php trailingslashit( the_permalink() ); ?>" method="get" class="mortgage-form">
                <div class="form-group">
                    <label><?php esc_html_e( 'Loan Amount :', 'homlisti-core' ); ?></label>
                    <input type="number" placeholder="<?php echo esc_attr( "100000" ) ?>" class="form-control rt_amount" name="rt_amount" required>
                </div>
                <div class="form-group">
                    <label><?php esc_html_e( 'Down Payment :', 'homlisti-core' ); ?></label>
                    <input type="number" placeholder="5%" class="form-control rt_deposit" name="rt_deposit" required>
                </div>
                <div class="form-group">
                    <label><?php esc_html_e( 'Years', 'homlisti-core' ); ?></label>
                    <input type="number" placeholder="<?php esc_attr_e( '12 Years', 'homlisti-core' ); ?>" class="form-control rt_year"
                           name="rt_year" required>
                </div>
                <div class="form-group">
                    <label><?php esc_html_e( 'Interest Rate', 'homlisti-core' ); ?></label>
                    <input type="number" class="form-control rt_rate" step="0.01" placeholder="10%" name="rt_rate" required>
                </div>
                <div class="form-group form-btn">
                    <button type="submit" name="rt_calculate" class="submit-btn"><?php echo esc_html( $atts['buttonText'] ); ?></button>
                    <a class='reset-btn'><i class="fas fa-sync-alt"></i><?php esc_html_e( ' Reset Form', 'homlisti-core' ); ?></a>
                </div>
            </form>
            <div class="emi-text"></div>
        </div>
		<?php
		return ob_get_clean();
	}

	public function rt_emi_calculator() {
		?>
        <script type="application/javascript">
            ;(function ($) {
                var emi_result = $('#mortgage-calculator .emi-text');
                var mortgage_form = $('#mortgage-calculator .mortgage-form');
                mortgage_form.on('submit', function (e) {
                    e.preventDefault();
                    var rt_amount = $(this).find('.rt_amount').val();
                    var rt_deposit = $(this).find('.rt_deposit').val();
                    var rt_year = $(this).find('.rt_year').val();
                    var rt_rate = $(this).find('.rt_rate').val();

                    //Mortgage Calculation
                    var deposit = (rt_amount * rt_deposit) / 100;
                    //Loan Amount
                    var loan = rt_amount - deposit;
                    // Interest Rate as Month
                    var rate = rt_rate / (12 * 100);
                    // Total Month
                    var months = rt_year * 12;
                    // Calculation
                    var k = Math.pow(1 + rate, months);

                    var value = Math.ceil(loan * rate * (k / (k - 1)));

                    emi_result.html("<span><?php echo esc_html( "Monthly Payment", "homlisti-core" ) ?> " + value + "</span>");
                    emi_result.slideDown(600);
                });


                $('.mortgage-calculator .form-group .reset-btn').on('click', function (e) {
                    e.preventDefault();
                    $(':input', '.mortgage-form')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .removeAttr('checked')
                        .removeAttr('selected');

                    $(".mortgage-calculator .emi-text span").remove();
                });

            })(jQuery);
        </script>
		<?php
	}

}

Shortcodes::instance();