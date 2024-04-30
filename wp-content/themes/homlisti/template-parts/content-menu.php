<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;
$header_style = RDTheme::$header_style ? RDTheme::$header_style : 4;
?>
    <header id="site-header" class="site-header">
		<?php
		if ( RDTheme::$has_top_bar ) {
			get_template_part( 'template-parts/header/header-top', '3' );
		}
		get_template_part( 'template-parts/header/header', $header_style );
		?>
    </header>

	<?php get_template_part( 'template-parts/header/header', 'offscreen' ); ?>