<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

$header_container = 'container';
if ( 'fullwidth' == RDTheme::$header_width ) {
	$header_container = 'container-fluid';
}
?>
<div id="rt-sticky-placeholder"></div>
<div id="header-menu" class="header-menu menu-layout1 header-icon-round">
    <div class="<?php echo esc_attr( $header_container ); ?>">
        <div class="header-content">
			<?php get_template_part( 'template-parts/header/site', 'logo' ) ?>
            <div id="main-navigation" class="navigation-area <?php echo esc_attr( RDTheme::$menu_alignment ) ?>">
				<?php wp_nav_menu( [
					'theme_location'  => 'primary',
					'container'       => 'nav',
					'container_id'    => 'dropdown',
					'container_class' => 'template-main-menu',
				] ); ?>
            </div>
			<?php get_template_part( 'template-parts/header/listing', 'area' ) ?>
        </div>
    </div>
</div>