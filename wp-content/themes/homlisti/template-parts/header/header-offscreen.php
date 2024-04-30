<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;
$site_name          = get_bloginfo( 'name' );
$custom_logo_id     = get_theme_mod( 'custom_logo' );
$default_logo       = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' ) : [
	Helper::get_img( 'logo.svg' ),
	157,
	40
];
$default_light_logo = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'full' ) : [
	Helper::get_img( 'logo_light.svg' ),
	157,
	40
];
$main_logo          = ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo'], 'full' ) : $default_logo;
$light_logo         = ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo_light'], 'full' )
	: $default_light_logo;
$mobile_logo        = ( isset( RDTheme::$options['mobile_logo'] ) && 0 != RDTheme::$options['mobile_logo'] ) ? wp_get_attachment_image_src( RDTheme::$options['mobile_logo'],
	'full' )
	: '';

if ( ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] ) && ! ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] ) ) {
	$mobile_logo = $main_logo;
}

if ( ! ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] ) && ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] ) ) {
	$mobile_logo = $light_logo;
}

if ( RDTheme::$has_tr_header ) {
	$logo = $light_logo;
} else {
	$logo = $main_logo;
}
?>
<div id="mobile-menu-sticky-placeholder"></div>
<div class="rt-header-menu mean-container mobile-offscreen-menu header-icon-round" id="meanmenu">
    <div class="mean-bar">
        <div class="mobile-logo <?php echo esc_attr( ! empty( $mobile_logo ) ? 'has-mobile-logo' : '' ) ?>">
			<?php if ( ! empty( $logo ) ): ?>
                <a class="custom-logo site-main-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img class="img-fluid" src="<?php echo esc_url( $logo[0] ); ?>" width="<?php echo esc_attr( $logo[1] ); ?>" height="<?php echo esc_attr( $logo[2] ); ?>"
                         alt="<?php echo esc_attr( $site_name ); ?>">
                </a>
			<?php else: ?>
                <h1 class="site-title site-main-logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'homlisti' ); ?>" rel="home">
						<?php echo esc_html( $site_name ); ?>
                    </a>
                </h1>
			<?php endif; ?>
			<?php if ( ! empty( $mobile_logo ) ) : ?>
                <a class="custom-logo site-mobile-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img class="img-fluid" src="<?php echo esc_url( $mobile_logo[0] ); ?>" width="<?php echo esc_attr( $mobile_logo[1] ); ?>"
                         height="<?php echo esc_attr( $mobile_logo[2] ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
                </a>
			<?php endif; ?>
        </div>

		<?php get_template_part( 'template-parts/header/listing', 'area' ) ?>

    </div>

    <div class="rt-slide-nav">
        <div class="offscreen-navigation">
			<?php wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => 'nav',
			] ); ?>
        </div>
    </div>
</div>
