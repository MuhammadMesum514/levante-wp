<?php
/**
 * @var string $phone
 * @var string $whatsapp_number
 * @var string $email
 * @var string $website
 * @var array $phone_options
 * @var bool $has_contact_form
 * @var string $email_to_seller_form
 * @var Listing $listing
 * @var array $locations
 * @var int $listing_id Listing id
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use radiustheme\HomListi\Helper;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;
use \radiustheme\HomListi\RDTheme;

global $listing;
$phone                      = get_post_meta( $listing->get_id(), 'phone', true );
$whatsapp                   = get_post_meta( $listing->get_id(), '_rtcl_whatsapp_number', true );
$email                      = get_post_meta( $listing->get_id(), 'email', true );
$website                    = get_post_meta( $listing->get_id(), 'website', true );
$rating_count               = $listing->get_rating_count();
$average_rating             = $listing->get_average_rating();
$listing_owner_widget_title = RDTheme::$options['listing_owner_widget_title'];
$has_contact_form           = Functions::get_option_item( 'rtcl_moderation_settings', 'has_contact_form', false, 'checkbox' )
?>

<div class="rtcl-listing-user-info">
	<?php if ( $phone || $email || $website || $whatsapp ) : ?>
        <div class="widget widget-contact-form list-group">
            <h3 class="widget-heading">
				<?php
				if ( $listing_owner_widget_title ) {
					echo esc_html( $listing_owner_widget_title );
				} else {
					echo esc_html__( "Contact Listing Owner", 'homlisti' );
				}
				?>
            </h3>

            <div class="rtcl-member-store-info">

                <div class="media mt-3">
                    <a class="mr-3"
                       href="<?php echo method_exists( $listing, 'get_the_author_url' ) ? esc_url( $listing->get_the_author_url() ) : '#'; ?>">
						<?php Helper::get_listing_author_iamge( $listing ); ?>
                    </a>
                    <div class="media-body">
                        <h4 class="mt-0">
                            <a href="<?php echo method_exists( $listing, 'get_the_author_url' ) ? esc_url( $listing->get_the_author_url() ) : '#'; ?>">
								<?php echo esc_html( $listing->get_author_name() ); ?>
								<?php do_action( 'rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                            </a>
                        </h4>

						<?php if ( class_exists('RtclPro') && Fns::registered_user_only( 'listing_seller_information' ) && ! is_user_logged_in() ) { ?>
                            <p class="login-message"><?php echo wp_kses( sprintf( __( "Please <a href='%s'>login</a> to view the seller information.", "homlisti" ), esc_url( Link::get_my_account_page_link() ) ), [ 'a' => [ 'href' => [] ] ] ); ?></p>
						<?php } else { ?>
							<?php if ( $phone ) : ?>

								<?php
								$mobileClass = wp_is_mobile() ? " rtcl-mobile" : null;
								$phone_options = [
									'safe_phone'   => mb_substr($phone, 0, mb_strlen($phone) - 3) . apply_filters('rtcl_phone_number_placeholder', 'XXX'),
									'phone_hidden' => mb_substr($phone, -3)
								];
								?>
                                <div class='item-number phone reveal-phone<?php echo esc_attr($mobileClass); ?>'
                                     data-options="<?php echo $phone_options ? htmlspecialchars(wp_json_encode($phone_options)) : ''; ?>">
                                    <i class="fa fa-phone-alt"></i>
                                    <div class='numbers'>

                                        <?php echo esc_html($phone_options['safe_phone']); ?>
                                    </div>
                                    <small class='text-muted'><?php esc_html_e("(Show)","homlisti") ?></small>
                                </div>
							<?php endif; ?>

							<?php if ( $whatsapp ) : ?>
								<?php
								$mobileClass = wp_is_mobile() ? " rtcl-mobile" : null;
								$phone_options = [
									'safe_phone'   => mb_substr($whatsapp, 0, mb_strlen($whatsapp) - 3) . apply_filters('rtcl_phone_number_placeholder', 'XXX'),
									'phone_hidden' => mb_substr($whatsapp, -3)
								];
								?>
                                <div class='item-number whatsapp reveal-phone<?php echo esc_attr($mobileClass); ?>'
                                     data-options="<?php echo $phone_options ? htmlspecialchars(wp_json_encode($phone_options)) : ''; ?>">
                                    <i class="fab fa-whatsapp"></i>
                                    <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $whatsapp ); ?>&text=<?php echo get_the_title(); ?>">
                                    <div class='numbers'>
										<?php echo esc_html($phone_options['safe_phone']); ?>
                                    </div>
                                    </a>
                                    <small class='text-muted'><?php esc_html_e("(Show)","homlisti") ?></small>
                                </div>
							<?php endif; ?>

							<?php if ( $email ) : ?>
                                <div class="agency-email listing-mail">
                                    <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                </div>
							<?php endif; ?>

							<?php if ( ! empty( $rating_count ) ): ?>
                                <div class="product-rating listing-raing">
                                    <div class="item-icon">
										<?php echo Functions::get_rating_html( $average_rating, $rating_count ); ?>
                                    </div>
                                    <div class="item-text"><?php echo apply_filters( 'homlisti_rating_count_format',
											sprintf( __( '(<span>%s</span>) Reviews', 'homlisti' ), esc_html( $rating_count ) ) ); ?></div>
                                </div>
							<?php endif; ?>
						<?php } ?>
                    </div>
                </div>
            </div>

			<?php if ( class_exists('RtclPro') && Fns::registered_user_only( 'listing_seller_information' ) && ! is_user_logged_in() ) {
				//do something
			} else { ?>
                <div class="rtcl-chat-website-link">
					<?php
					if ( class_exists('RtclPro') && Fns::is_enable_chat() && is_user_logged_in() ):
						$chat_btn_class = [ 'rtcl-chat-link' ];
						$chat_url = Link::get_my_account_page_link( 'chat' );
						$is_chat = 'rtcl-contact-link';
						$chat_label = __( 'Live Chat', 'homlisti' );
						if ( is_user_logged_in() && $listing->get_author_id() !== get_current_user_id() ) {
							$chat_url   = '#';
							$chat_label = __( 'Quick Chat', 'homlisti' );
							$is_chat    = 'rtcl-contact-seller';
							array_push( $chat_btn_class, 'rtcl-contact-seller' );
						}
						?>
                        <div class='<?php echo esc_attr( $is_chat ); ?>'>
                            <a class="btn btn-primary <?php echo esc_attr( implode( ' ', $chat_btn_class ) ) ?>"
                               href="<?php echo esc_url( $chat_url ) ?>" data-listing_id="<?php the_ID() ?>">
                                <i class="fas fa-comment"></i>
								<?php echo esc_html( $chat_label ) ?>
                                <span class="rtcl-chat-unread-count"></span>
                            </a>
                        </div>
					<?php endif; ?>

					<?php if ( $website ) : ?>
                        <div class='rtcl-website'>
                            <a class="rtcl-website-link btn btn-primary" href="<?php echo esc_url( $website ); ?>"
                               target="_blank"<?php echo Functions::is_external( $website ) ? ' rel="nofollow"' : ''; ?>><span
                                        class='rtcl-icon rtcl-icon-globe text-white'></span><?php esc_html_e( "Visit Website", "homlisti" ) ?>
                            </a>
                        </div>
					<?php endif; ?>
                </div>

				<?php if ( $has_contact_form && $email ) : ?>
                    <div class='rtcl-do-email'>
						<?php $listing->email_to_seller_form(); ?>
                    </div>
				<?php endif; ?>
			<?php } ?>
        </div>
	<?php endif; ?>
</div>