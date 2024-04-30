<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.4
 */

namespace radiustheme\HomListi;
?>
</div><!-- #content -->
<?php

use Rtcl\Helpers\Functions;

$footer_style = RDTheme::$footer_style ? RDTheme::$footer_style : 2;
get_template_part( 'template-parts/footer/footer', $footer_style ); ?>
</div><!-- #page -->
<?php wp_footer(); ?>


</body>
</html>