<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

use radiustheme\HomListi\RDTheme;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;

$login_icon_title = is_user_logged_in() ? esc_html__( " My Account", 'homlisti' ) : esc_html__( " Sign in", 'homlisti' );
?>

<div class="listing-area">
    <div class="header-action">
        <ul class="header-btn">

			<?php if ( class_exists( 'Rtcl' ) && class_exists('RtclPro') && Fns::is_enable_compare() && RDTheme::$options['header_compare_icon'] ):
				$compare_ids = rtcl()->session->get( 'rtcl_compare_ids', [] );
				if ( ! empty( $compare_ids ) || is_array( $compare_ids ) ) {
					$compare_ids = count( $compare_ids );
				}
				if ( RDTheme::$options['compare_btn_order'] ) {
					$compare_btn_order = "order:" . RDTheme::$options['compare_btn_order'];
				} elseif ( RDTheme::$header_style == "2" ) {
					$compare_btn_order = "order:3";
				} else {
					$compare_btn_order = "";
				}
				?>
                <li class="compare-btn has-count-number button" style="<?php echo esc_attr( $compare_btn_order ); ?>">
                    <a class="item-btn"
                       data-toggle="tooltip"
                       data-placement="bottom"
                       title="<?php echo esc_attr__( 'Compare', 'homlisti' ); ?>"
                       href="<?php echo esc_url( Link::get_page_permalink( 'compare_page' ) ); ?>">
                        <i class="flaticon-left-and-right-arrows icon-round"></i>
                        <span class="count rt-compare-count"><?php echo esc_html( $compare_ids ) ?></span>
                    </a>
                </li>
			<?php endif; ?>

			<?php if ( class_exists( 'rtcl' ) && Functions::is_enable_favourite() && RDTheme::$options['header_fav_icon'] ):
				$favourite_posts = get_user_meta( get_current_user_id(), 'rtcl_favourites', true );
				if ( ! empty( $favourite_posts ) || is_array( $favourite_posts ) ) {
					$favourite_posts = count( $favourite_posts );
				}
				$favourite_posts = $favourite_posts ? $favourite_posts : '0';
				if ( RDTheme::$options['fav_btn_order'] ) {
					$fav_btn_order = "order:" . RDTheme::$options['fav_btn_order'];
				} elseif ( RDTheme::$header_style == "2" ) {
					$fav_btn_order = "order:2";
				} else {
					$fav_btn_order = "";
				}
				?>
                <li class="favourite has-count-number button" style="<?php echo esc_attr( $fav_btn_order ) ?>">
                    <a class="item-btn"
                       data-toggle="tooltip"
                       data-placement="bottom"
                       title="<?php esc_attr_e( 'Favourites', 'homlisti' ); ?>"
                       href="<?php echo esc_url( Link::get_my_account_page_link( 'favourites' ) ); ?>">
                        <i class="flaticon-heart icon-round"></i>
                        <span class="count rt-header-favourite-count"><?php echo esc_html( $favourite_posts ) ?></span>
                    </a>
                </li>
			<?php endif; ?>

			<?php if ( class_exists( 'Rtcl' ) && RDTheme::$options['header_login_icon'] ):
				if ( RDTheme::$options['login_btn_order'] ) {
					$login_btn_order = "order:" . RDTheme::$options['login_btn_order'];
				} elseif ( RDTheme::$header_style == "2" ) {
					$login_btn_order = "order:1";
				} else {
					$login_btn_order = "";
				}
				?>
                <li class="login-btn button" style="<?php echo esc_attr( $login_btn_order ) ?>">
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
				echo "<li class='cart-icon button icon-hover-item' style='" . esc_attr( $cart_btn_order ) . "'>";
				get_template_part( 'template-parts/header/cart', 'icon' );
				echo "</li>";
			}
			?>

            <?php
			if ( RDTheme::$options['header_search_icon'] ) {
				if ( RDTheme::$options['search_btn_order'] ) {
					$search_btn_order = "order:" . RDTheme::$options['search_btn_order'];
				} elseif ( RDTheme::$header_style == "2" ) {
					$search_btn_order = "order:5";
				} else {
					$search_btn_order = "";
				}
				?>
                <li class="search-icon button icon-hover-item" style="<?php echo esc_attr( $search_btn_order ) ?>">
	                <?php get_template_part( 'template-parts/header/search', 'icon' ); ?>
<!--                    <a class="item-btn"-->
<!--                       data-toggle="tooltip"-->
<!--                       data-placement="bottom"-->
<!--                       title="--><?php //echo esc_attr( 'Search', 'homlisti' ); ?><!--"-->
<!--                       href="--><?php //echo esc_url( Link::get_my_account_page_link() ); ?><!--">-->
<!--                        <i class="fas fa-search icon-round"></i>-->
<!--                    </a>-->
                </li>
            <?php
			}
			?>

			<?php if ( RDTheme::$options['header_btn'] ):
				if ( RDTheme::$options['header_btn_order'] ) {
					$header_btn_order = "order:" . RDTheme::$options['header_btn_order'];
				} elseif ( RDTheme::$header_style == "2" ) {
					$header_btn_order = "order:4";
				} else {
					$header_btn_order = "";
				}
				?>
                <li class="submit-btn header-add-property-btn" style="<?php echo esc_attr( $header_btn_order ); ?>">
                    <a href="<?php echo esc_url( RDTheme::$options['header_btn_url'] ); ?>" class="item-btn rt-animation-btn">
						<span>
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        <div class="btn-text"><?php echo esc_html( RDTheme::$options['header_btn_txt'] ); ?></div>
                    </a>
                </li>
			<?php endif; ?>

            <li class="offcanvar_bar button" style="order: 99">
                <span class="sidebarBtn ">
                    <span class="fa fa-bars">
                    </span>
                </span>
            </li>
        </ul>
    </div>
</div>
