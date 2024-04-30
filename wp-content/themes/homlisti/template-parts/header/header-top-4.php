<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

$has_top_info = RDTheme::$options['contact_address'] || RDTheme::$options['contact_phone'] || RDTheme::$options['contact_email'] || RDTheme::$options['contact_website'] ? true
	: false;
$socials      = Helper::socials();

if ( ! $has_top_info || ! $socials ) {
	return;
}
$header_container = 'container';
if ( 'fullwidth' == RDTheme::$header_width ) {
	$header_container = 'container-fluid';
}
?>

<div id="header-topbar" class="header-topbar">
    <div class="<?php echo esc_attr( $header_container ); ?>">
        <div class="row d-flex align-items-center">
			<?php if ( $has_top_info ): ?>
                <div class="col-sm-7 col-7">
                    <ul class="topbar-left">
	                    <?php if ( RDTheme::$options['contact_phone'] ): ?>
                            <li class="item-location"><i class="fas fa-phone"></i><span><?php echo esc_html( RDTheme::$options['contact_phone'] ); ?></span></li>
	                    <?php endif; ?>
						<?php if ( RDTheme::$options['contact_address'] ): ?>
                            <li class="item-location"><i class="fas fa-map-marker-alt"></i><span><?php echo esc_html( RDTheme::$options['contact_address'] ); ?></span></li>
						<?php endif; ?>
                    </ul>
                </div>
			<?php endif; ?>
			<?php if ( $socials ): ?>
                <div class="col-sm-5 col-5 d-flex justify-content-end">
                    <ul class="topbar-right">
						<?php if ( $socials ): ?>
                            <li class="social-icon">
                                <label><?php esc_html_e( 'Follow Us On:', 'homlisti' ); ?></label>
								<?php foreach ( $socials as $social ): ?>
                                    <a target="_blank" href="<?php echo esc_url( $social['url'] ); ?>"><i class="<?php echo esc_attr( $social['icon'] ); ?>"></i></a>
								<?php endforeach; ?>
                            </li>
						<?php endif; ?>
                    </ul>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>