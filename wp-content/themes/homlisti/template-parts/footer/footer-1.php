<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

$footer_columns = 0;

foreach ( range( 1, 4 ) as $i ) {
	if ( is_active_sidebar( 'footer-' . $i ) ) {
		$footer_columns ++;
	}
}

switch ( $footer_columns ) {
	case '1':
		$footer_class = 'col-sm-12 col-12';
		break;
	case '2':
		$footer_class = 'col-sm-6 col-12';
		break;
	case '3':
		$footer_class = 'col-md-4 col-sm-12 col-12';
		break;
	default:
		$footer_class = 'col-lg-3 col-sm-6 col-12';
}
$copyright_class = has_nav_menu( 'secondary' ) ? 'col-xl-6 col-lg-4 text-right' : 'col-sm-12 col-12 text-center';
$is_border       = RDTheme::$footer_border ? 'is-border' : '';
?>
<footer id="site-footer" class="site-footer footer-wrap footer-style-1 <?php echo esc_attr( $is_border ) ?>">
	<?php if ( $footer_columns ): ?>
        <div class="main-footer">
            <div class="container">
                <div class="row">
					<?php
					foreach ( range( 1, 4 ) as $i ) {
						if ( ! is_active_sidebar( 'footer-' . $i ) ) {
							continue;
						}
						echo '<div class="' . esc_attr( $footer_class ) . '">';
						dynamic_sidebar( 'footer-' . $i );
						echo '</div>';
					}
					?>
                </div>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( RDTheme::$options['copyright_area'] ): ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
					<?php if ( has_nav_menu( 'secondary' ) ): ?>
                        <div class="col-xl-6 col-lg-8">
                            <div class="footer-bottom-menu">
								<?php
								wp_nav_menu( [
									'theme_location' => 'secondary',
									'menu_class'     => 'footer-link',
									'fallback_cb'    => false,
								] );
								?>
                            </div>
                        </div>
					<?php endif; ?>
                    <div class="<?php echo esc_attr( $copyright_class ); ?>">
                        <p class="footer-copyright">
							<?php
							echo wp_kses( RDTheme::$options['copyright_text'], [
								'a'      => [
									'href'  => [],
									'title' => [],
								],
								'br'     => [],
								'em'     => [],
								'strong' => [],
							] );
							?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>
</footer>