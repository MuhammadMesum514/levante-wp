<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4.1
 */

namespace radiustheme\HomListi;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Helper::requires( 'dynamic-styles/common.php' );

$primary_color   = Helper::get_primary_color(); // #00c194
$primary_light   = RDTheme::$options['primary_lighiten']; //#50ffe4
$primary_light2  = RDTheme::$options['primary_lighiten2']; //#50ffe4
$primary_light3  = RDTheme::$options['primary_lighiten3']; //#50ffe4
$primary_dark    = RDTheme::$options['primary_dark']; //#50ffe4
$secondary_color = Helper::get_secondary_color(); // #07c196
$body_color      = Helper::get_body_color(); // #788593

$primary_rgb   = Helper::hex2rgb( $primary_color );
$secondary_rgb = Helper::hex2rgb( $secondary_color );

if ( ! $primary_light ) {
	$primary_light = Helper::rt_modify_color( $primary_color, 80 );
}
if ( ! $primary_dark ) {
	$primary_dark = Helper::rt_modify_color( $primary_color, - 30 );
}

$menu_color                   = RDTheme::$options['menu_color'];
$transparent_menu_color       = RDTheme::$options['transparent_menu_color'];
$transparent_menu_color_hover = RDTheme::$options['transparent_menu_color_hover'];
$menu_background              = RDTheme::$options['menu_background'];
$sticky_menu_background       = RDTheme::$options['sticky_menu_background'];
$sub_menu_color               = RDTheme::$options['sub_menu_color'];
$menu_hover_color             = RDTheme::$options['menu_hover_color'];
$menu_arrow_color             = RDTheme::$options['menu_arrow_color'];
$btn_color                    = RDTheme::$options['btn_color'];
$btn_hover_color              = RDTheme::$options['btn_hover_color'];
$menu_icon_color              = RDTheme::$options['menu_icon_color'];
$menu_icon_hover_color        = RDTheme::$options['menu_icon_hover_color'];
$header_transparent_color     = RDTheme::$options['header_transparent_color'];
$breadcrumb_bg1               = RDTheme::$options['breadcrumb_bg1'];
$breadcrumb_bg2               = RDTheme::$options['breadcrumb_bg2'];
$breadcrumb_color             = RDTheme::$options['breadcrumb_color'];
$breadcrumb_title_color             = RDTheme::$options['breadcrumb_title_color'];
$breadcrumb_active_color      = RDTheme::$options['breadcrumb_active_color'];
$footer_bg                    = RDTheme::$options['footer_bg'];
$footer2_bg_overlay           = RDTheme::$options['footer2_bg_overlay'];
$footer2_bg_overlay_opacity   = RDTheme::$options['footer2_bg_overlay_opacity'];
$footer2_copyright_bg         = RDTheme::$options['footer2_copyright_bg'];
$footer2_copyright_text_color = RDTheme::$options['footer2_copyright_text_color'];
$footer_title_border_color    = RDTheme::$options['footer_title_border_color'];
$footer_text                  = RDTheme::$options['footer_text_color'];
$footer2_text                 = RDTheme::$options['footer2_text_color'];
$footer_text_hover            = RDTheme::$options['footer_text_hover'];
$footer2_text_hover           = RDTheme::$options['footer2_text_hover'];
$copyright_bg                 = RDTheme::$options['copyright_bg'];
$copyright_text               = RDTheme::$options['copyright_text_color'];
$footer_title                 = RDTheme::$options['footer_title_color'];
$footer2_title                = RDTheme::$options['footer2_title_color'];
$copyright_menu_color         = RDTheme::$options['copyright_menu_color'];
$footer_icon_circle_color     = RDTheme::$options['footer_icon_circle_color'];
$footer2_icon_circle_color    = RDTheme::$options['footer2_icon_circle_color'];
$main_logo_width_height       = RDTheme::$options['main_logo_width_height'];

$logo_max_width = $logo_max_height = '';
if ( $main_logo_width_height ) {
	[ $logo_max_width, $logo_max_height ] = explode( ',', $main_logo_width_height );
}

$menu_color              = $menu_color ? $menu_color : $secondary_color;
$menu_hover_color        = $menu_hover_color ? $menu_hover_color : $primary_color;
$menu_arrow_color        = $menu_arrow_color ? $menu_arrow_color : $menu_color;
$menu_icon_color         = $menu_icon_color ? $menu_icon_color : $secondary_color;
$menu_icon_hover_color   = $menu_icon_hover_color ? $menu_icon_hover_color : $secondary_color;
$btn_color               = $btn_color ? $btn_color : $primary_color;
$btn_hover_color         = $btn_hover_color ? $btn_hover_color : $secondary_color;
$breadcrumb_active_color = $breadcrumb_active_color ? $breadcrumb_active_color : $primary_color;
?>
<?php
/*-------------------------------------
#. Defaults
---------------------------------------*/
?>
:root {
--rt-primary-color: <?php echo esc_html( $primary_color ? $primary_color : '#00c194' ); ?>;
--rt-primary-dark: <?php echo esc_html( $primary_dark ? $primary_dark : '#00a376' ); ?>;
--rt-primary-light: <?php echo esc_html( $primary_light ? $primary_light : '#50ffe4' ); ?>;
--rt-primary-light2: <?php echo esc_html( $primary_light2 ? $primary_light2 : '#dceeea' ); ?>;
--rt-primary-light3: <?php echo esc_html( $primary_light3 ? $primary_light3 : '#EAF7F4' ); ?>;
--rt-secondary-color: <?php echo esc_html( $secondary_color ? $secondary_color : '#07c196' ); ?>;
--rt-primary-rgb: <?php echo esc_html( $primary_rgb ? $primary_rgb : '0, 193, 148' ); ?>;
--rt-secondary-rgb: <?php echo esc_html( $secondary_rgb ? $secondary_rgb : '7, 193, 150' ); ?>;
}


.elementor-kit-2673 {
--e-global-color-primary: <?php echo esc_html( $primary_color ? $primary_color : '#00c194' ); ?>;
--e-global-color-secondary: <?php echo esc_html( $secondary_color ? $secondary_color : '#00a376' ); ?>;
--e-global-color-accent: <?php echo esc_html( $primary_color ? $primary_color : '#00c194' ); ?>;
--e-global-color-d22c469: <?php echo esc_html( $primary_dark ? $primary_dark : '#00a376' ); ?>;
--e-global-color-4f65493: <?php echo esc_html( $primary_light2 ? $primary_light2 : '#dceeea' ); ?>;
--e-global-color-2ab0c7b: <?php echo esc_html( $primary_light3 ? $primary_light3 : '#EAF7F4' ); ?>;
}

body {
color: <?php echo esc_html( $body_color ); ?>;
}

a:active, .rtcl a:hover, a:hover, a:focus {
color: <?php echo esc_html( $secondary_color ); ?>;
}

<?php if ( $logo_max_width || $logo_max_height ) : ?>
    .header-menu .header-content .logo-area img {
	<?php
	if ( $logo_max_width ) {
		echo esc_attr( "max-width:" . trim( $logo_max_width ) ) . ";";
	}
	?>
	<?php
	if ( $logo_max_height ) {
		echo esc_attr( "max-height:" . trim( $logo_max_height ) ) . ";";
	}
	?>
    }
<?php endif; ?>


<?php
/*-------------------------------------
#. Header
---------------------------------------*/
?>
.header-add-property-btn .item-btn{
background-color: <?php echo esc_html( $btn_color ); ?>;
}
.header-add-property-btn .item-btn::after{
background-color: <?php echo esc_html( $btn_hover_color ); ?>;
}

.mean-container a.meanmenu-reveal span {
background-color: <?php echo esc_html( $btn_color ); ?>;
}
.header-mobile-icons a.header-btn {
background-color: <?php echo esc_html( $btn_color ); ?>;
}
.header-mobile-icons a.header-btn:hover {
background-color: <?php echo esc_html( $btn_hover_color ); ?>;
}
.mean-container .mean-nav ul li a.mean-expand,
.mean-container a.meanmenu-reveal {
color: <?php echo esc_html( $btn_color ); ?>;
}
.header-style-4 .header-add-property-btn .item-btn,
.header-icon-round .header-action ul li.button a,
.navigation-area nav > ul > li > a {
color: <?php echo esc_html( $menu_color ); ?>;
}

<?php if ( $transparent_menu_color ) : ?>
    .trheader .header-menu .navigation-area nav > ul > li > a {
    color: <?php echo esc_html( $transparent_menu_color ); ?>;
    }
    .trheader .navigation-area nav > ul li.menu-item-has-children > a:after {
    border-color: <?php echo esc_html( $transparent_menu_color ); ?>;
    }
    .trheader .header-menu .navigation-area nav > ul > li.current-menu-ancestor > a {
    border-bottom-color: <?php echo esc_html( $transparent_menu_color ); ?>;
    }
<?php endif; ?>

<?php if ( $transparent_menu_color_hover ) : ?>
    .trheader .header-menu .navigation-area nav > ul > li > a:hover {
    color: <?php echo esc_html( $transparent_menu_color_hover ); ?>;
    border-bottom-color: <?php echo esc_html( $transparent_menu_color_hover ); ?>;
    }
    .trheader .navigation-area nav > ul li.menu-item-has-children > a:hover:after {
    border-color: <?php echo esc_html( $transparent_menu_color_hover ); ?>;
    }
    .trheader .header-menu .navigation-area nav > ul > li.current_page_item > a,
    .trheader .header-menu .navigation-area nav > ul > li.current-menu-ancestor > a,
    .trheader .header-menu .navigation-area nav > ul > li.current-menu-item > a,
    .trheader .header-menu .navigation-area nav > ul > li.current-menu-ancestor > a:hover {
    border-bottom-color: <?php echo esc_html( $transparent_menu_color_hover ); ?>;
    color: <?php echo esc_html( $transparent_menu_color_hover ); ?>;
    }

<?php endif; ?>

.navigation-area nav > ul > li ul.sub-menu li a {
color: <?php echo esc_html( $sub_menu_color ); ?>;
}
.header-icon-round .header-action ul li.button a:hover,
.navigation-area nav > ul > li ul.sub-menu li a:hover,
.header-menu .navigation-area nav ul li.current-menu-item a,
.header-menu .navigation-area nav > ul > li > a:hover {
color: <?php echo esc_html( $menu_hover_color ); ?>;
}

.header-icon-round .header-action ul li.button a i {
color: <?php echo esc_html( $menu_icon_color ); ?>;
}
.header-icon-round .header-action ul li.button a:hover .icon-round {
background-color: <?php echo esc_html( $menu_icon_hover_color ); ?>;
border-color: <?php echo esc_html( $menu_icon_hover_color ); ?>;
}
.trheader .header-icon-round .header-action ul li.button a:hover .icon-round {
background-color: <?php echo esc_html( $primary_color ); ?>;
border-color: <?php echo esc_html( $primary_color ); ?>;
}
.header-topbar .topbar-right .social-icon a:hover{
color: <?php echo esc_html( $primary_light ); ?>;
}

<?php if ( $menu_background ) : ?>
    #header-menu {
    background-color: <?php echo esc_html( $menu_background ); ?>;
    }

<?php endif; ?>

<?php if ( $sticky_menu_background ) : ?>
    #header-menu.rt-sticky {
    background-color: <?php echo esc_html( $sticky_menu_background ); ?>;
    }
<?php endif; ?>

<?php //Transparent Header Color
?>
.trheader .site-header::before {
background: <?php echo esc_html( $header_transparent_color ); ?>;
background: -webkit-linear-gradient(top, <?php echo esc_html( $header_transparent_color ); ?> 0%, rgba(0, 0, 0, 0) 100%);
background: linear-gradient(to bottom, <?php echo esc_html( $header_transparent_color ); ?> 0%, rgba(0, 0, 0, 0) 100%);
}

<?php
/*-------------------------------------
#. Breadcrumb
---------------------------------------*/
?>
<?php if ( $breadcrumb_bg1 || $breadcrumb_bg2 ) : ?>
    .breadcrumbs-banner {
	<?php if ( $breadcrumb_bg1 && $breadcrumb_bg2 ) : ?>
        background-color: <?php echo esc_html( $breadcrumb_bg1 ); ?>;
        background-image: linear-gradient(to top, <?php echo esc_html( $breadcrumb_bg1 ); ?>, <?php echo esc_html( $breadcrumb_bg2 ); ?>)
	<?php elseif ( $breadcrumb_bg1 ) : ?>
        background-color: <?php echo esc_html( $breadcrumb_bg1 ); ?>;
	<?php elseif ( $breadcrumb_bg2 ) : ?>
        background-color: <?php echo esc_html( $breadcrumb_bg2 ); ?>;
	<?php endif; ?>
    }
<?php endif; ?>
.breadcrumbs-banner .rtcl-breadcrumb {
color: <?php echo esc_html( $breadcrumb_color ); ?>;
}
.breadcrumbs-banner h1 {
color: <?php echo esc_html( $breadcrumb_title_color ); ?>;
}
.breadcrumbs-banner .rtcl-breadcrumb a:hover,
.breadcrumbs-banner .rtcl-breadcrumb span {
color: <?php echo esc_html( $breadcrumb_active_color ); ?>;
}
<?php
/*-------------------------------------
#. Footer
---------------------------------------*/
?>
<?php if ( $footer_bg ) : ?>
    .footer-wrap {
    background-color: <?php echo esc_html( $footer_bg ); ?> !important;
    }
<?php endif; ?>


<?php if ( $footer2_bg_overlay ) : ?>
    .site-footer.footer-style-2.footer-wrap .main-footer::before {
    background-color: <?php echo esc_html( $footer2_bg_overlay ); ?>;
	<?php
	if ( $footer2_bg_overlay_opacity ) {
		echo esc_attr( "opacity:" . $footer2_bg_overlay_opacity ) . ";";
	}
	?>
    }
<?php endif; ?>


<?php if ( $footer2_copyright_bg ) : ?>
    .site-footer.footer-style-2.footer-wrap .footer-bottom {
    background-color: <?php echo esc_html( $footer2_copyright_bg ); ?>;
    }
<?php endif; ?>

<?php if ( $footer2_copyright_text_color ) : ?>
    .site-footer.footer-style-2.footer-wrap .footer-bottom * {
    color: inherit !important;
    }
    .site-footer.footer-style-2.footer-wrap .footer-bottom {
    color: <?php echo esc_html( $footer2_copyright_text_color ); ?>;
    }
<?php endif; ?>


<?php if ( $footer_title_border_color ) : ?>
    .footer-box .footer-title:after {
    background-color: <?php echo esc_html( $footer_title_border_color ); ?>;
    }
<?php endif; ?>

<?php if ( $footer_text ) : ?>
    .footer-box,
    .footer-box p,
    .footer-box a,
    .footer-box.widget_nav_menu ul.menu li a,
    .footer-box.widget_nav_menu ul.menu li a {
    color: <?php echo esc_html( $footer_text ); ?> !important;
    }
<?php endif; ?>

<?php if ( $footer2_text ) : ?>
    .footer-style-2 .footer-box,
    .footer-style-2 .footer-box p,
    .footer-style-2 .footer-box a,
    .footer-style-2 .footer-box.widget_nav_menu ul.menu li a,
    .footer-style-2 .footer-box.widget_nav_menu ul.menu li a {
    color: <?php echo esc_html( $footer2_text ); ?> !important;
    }
<?php endif; ?>

<?php if ( $footer_text_hover ) : ?>
    .footer-box.widget_nav_menu ul.menu li a:hover,
    .rt-contact-wrapper ul li a:hover,
    .main-footer .footer-box a:hover {
    color: <?php echo esc_html( $footer_text_hover ); ?> !important;
    }
<?php endif; ?>
<?php if ( $footer2_text_hover ) : ?>
    .footer-style-2 .footer-box.widget_nav_menu ul.menu li a:hover,
    .footer-style-2 .rt-contact-wrapper ul li a:hover,
    .footer-style-2 .main-footer .footer-box a:hover {
    color: <?php echo esc_html( $footer2_text_hover ); ?> !important;
    }
<?php endif; ?>

<?php if ( $footer_title ) : ?>
    .footer-box .footer-title {
    color: <?php echo esc_html( $footer_title ); ?> !important;
    }
<?php endif; ?>
<?php if ( $footer2_title ) : ?>
    .footer-style-2  .footer-box .footer-title {
    color: <?php echo esc_html( $footer2_title ); ?> !important;
    }
<?php endif; ?>
<?php if ( $copyright_bg ) : ?>
    .footer-bottom {
    background-color: <?php echo esc_html( $copyright_bg ); ?> !important;
    }
<?php endif; ?>
<?php if ( $copyright_text ) : ?>
    .footer-bottom .footer-copyright {
    color: <?php echo esc_html( $copyright_text ); ?> !important;
    }
<?php endif; ?>
<?php if ( $copyright_menu_color ) : ?>
    .footer-bottom .footer-link li a {
    color: <?php echo esc_html( $copyright_menu_color ); ?> !important;
    }
<?php endif; ?>

<?php if ( $footer_icon_circle_color ) : ?>
    .footer-style-2 .footer-box.widget_recent_comments ul li::before,
    .footer-style-2 .footer-box.widget_meta ul li a::before,
    .footer-style-2 .footer-box.widget_pages ul li a::before,
    .footer-style-2 .footer-box.widget_categories ul li a::before,
    .footer-style-2 .footer-box.widget_archive ul li a::before,
    .footer-style-2 .footer-box.widget_nav_menu ul li a::before {
    background-color: <?php echo esc_html( $footer_icon_circle_color ); ?> !important;
    }
    .rt-contact-wrapper ul li i {
    color: <?php echo esc_html( $footer_icon_circle_color ); ?> !important;
    }
<?php endif; ?>

<?php if ( $footer2_icon_circle_color ) : ?>
    .footer-style-2 .footer-box.widget_recent_comments ul li::before,
    .footer-style-2 .footer-box.widget_meta ul li a::before,
    .footer-style-2 .footer-box.widget_pages ul li a::before,
    .footer-style-2 .footer-box.widget_categories ul li a::before,
    .footer-style-2 .footer-box.widget_archive ul li a::before,
    .footer-style-2 .footer-box.widget_nav_menu ul li a::before {
    background-color: <?php echo esc_html( $footer2_icon_circle_color ); ?> !important;
    }
    .footer-style-2 .rt-contact-wrapper ul li i {
    color: <?php echo esc_html( $footer2_icon_circle_color ); ?> !important;
    }
<?php endif; ?>

<?php
/*-------------------------------------
#. Others
---------------------------------------*/
?>

.navigation-area nav > ul li.page_item_has_children > a:after,
.navigation-area nav > ul li.menu-item-has-children > a:after {
border-color: <?php echo esc_html( $menu_arrow_color ); ?>;
}
