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
                <div class="col-sm-7 col-9">
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

            <div class="col-sm-5 col-3 d-flex justify-content-end align-items-center">
                <ul class="topbar-right">
					<?php if ( $socials ): ?>
                        <li class="social-icon">
							<?php foreach ( $socials as $social ): ?>
                                <a target="_blank" href="<?php echo esc_url( $social['url'] ); ?>"><i class="<?php echo esc_attr( $social['icon'] ); ?>"></i></a>
							<?php endforeach; ?>
                        </li>
					<?php endif; ?>
                </ul>

				<?php if ( RDTheme::$options['header_btn'] ): ?>
                    <div class="header-add-property-btn" style="order: <?php echo esc_attr( RDTheme::$options['header_btn_order'] ) ?>">
                        <a href="<?php echo esc_url( RDTheme::$options['header_btn_url'] ); ?>" class="item-btn rt-animation-btn">
                            <span>
                                <i class="fas fa-plus-circle"></i>
                            </span>
                            <div class="btn-text"><?php echo esc_html( RDTheme::$options['header_btn_txt'] ); ?></div>
                        </a>
                    </div>
				<?php endif; ?>
            </div>

        </div>
    </div>
</div>