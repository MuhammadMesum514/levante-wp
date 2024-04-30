<?php
/**
 * Listing Form
 *
 * @var int    $category_id
 * @var string $selected_type
 * @version   1.0.0
 *
 * @author    RadiusTheme
 * @package   classified-listing/templates
 */

?>


<div class="rtcl rtcl-user rtcl-post-form-wrap homlisti-listing-form">
	<?php do_action( "rtcl_listing_form_before", $post_id ); ?>
    <form action="" method="post" id="rtcl-post-form" class="form-vertical" enctype="multipart/form-data">
		<?php do_action( "rtcl_listing_form_start", $post_id ); ?>
        <div class="rtcl-post">
			<?php do_action( "rtcl_listing_form", $post_id ); ?>
        </div>
		<?php do_action( "rtcl_listing_form_end", $post_id ); ?>
    </form>
	<?php do_action( "rtcl_listing_form_after", $post_id ); ?>
</div>
