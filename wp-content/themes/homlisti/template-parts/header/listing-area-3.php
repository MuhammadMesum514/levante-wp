<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use radiustheme\HomListi\RDTheme;
use Rtcl\Helpers\Link;

$login_icon_title = is_user_logged_in() ? esc_html__( " My Account", 'homlisti' ) : esc_html__( " Sign in", 'homlisti' );
?>

<div class="listing-area">
    <div class="header-action">
        <ul class="header-btn">

			<?php if ( RDTheme::$options['header_btn'] ): ?>
                <li class="submit-btn header-add-property-btn" style="order:<?php echo esc_attr( RDTheme::$options['header_btn_order'] ); ?>">
                    <a href="<?php echo esc_url( RDTheme::$options['header_btn_url'] ); ?>" class="item-btn rt-animation-btn">
						<span>
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        <div class="btn-text"><?php echo esc_html( RDTheme::$options['header_btn_txt'] ); ?></div>
                    </a>
                </li>
			<?php endif; ?>


			<?php if ( class_exists( 'Rtcl' ) && RDTheme::$options['header_login_icon'] ):
                ?>
                <li class="login-btn" style="order:<?php echo esc_attr( RDTheme::$options['login_btn_order'] ) ?>">
                    <a class="item-btn"
                       data-toggle="tooltip"
                       data-placement="bottom"
                       title="<?php echo esc_attr( $login_icon_title ); ?>"
                       href="<?php echo esc_url( Link::get_my_account_page_link() ); ?>">
                        <i class="flaticon-user-1 icon-round"></i>
                    </a>
                </li>
			<?php endif; ?>

	        <?php
	        if ( RDTheme::$options['header_cart_icon'] && class_exists( 'WC_Widget_Cart' ) ) {
		        if ( RDTheme::$options['cart_btn_order'] ) {
			        $cart_btn_order = "order:" . RDTheme::$options['cart_btn_order'];
		        } elseif ( RDTheme::$header_style == "2" ) {
			        $cart_btn_order = "order:4";
		        } else {
			        $cart_btn_order = "";
		        }
		        echo "<li class='cart-icon button' style='" . esc_attr( $cart_btn_order ) . "'>";
		        get_template_part( 'template-parts/header/cart', 'icon' );
		        echo "</li>";
	        }
	        ?>

            <li class="offcanvar_bar" style="order: 99">
                <span class="sidebarBtn ">
                    <span class="fa fa-bars">
                    </span>
                </span>
            </li>
        </ul>
    </div>
</div>
