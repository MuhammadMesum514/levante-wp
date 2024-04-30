<?php
/**
 * Listing meta
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 */

global $listing;

if ( ! $listing->can_show_date() && ! $listing->can_show_user() && ! $listing->can_show_location() && ! $listing->can_show_views() ) {
	return;
}
?>

<ul class="entry-meta">
	<?php if ( $listing->can_show_user() ): ?>
        <li class="author">
            <i class="fas fa-user"></i><?php esc_html_e( 'by ', 'homlisti' ); ?>
			<?php $listing->the_author(); ?>
        </li>
	<?php endif; ?>
	<?php if ( $listing->has_location() && $listing->can_show_location() ): ?>
        <li><i class="fas fa-map-marker-alt"></i><?php $listing->the_locations(); ?></li>
	<?php endif; ?>
	<?php if ( $listing->can_show_date() ): ?>
        <li class="updated"><i class="far fa-clock"></i><?php $listing->the_time(); ?></li>
	<?php endif; ?>
	<?php if ( $listing->can_show_views() ): ?>
        <li class="rt-views">
            <i class="far fa-eye"></i>
            <?php echo sprintf( _n( "%s view", "%s views", $listing->get_view_counts(), 'homlisti' ),
				number_format_i18n( $listing->get_view_counts() ) ); ?>
        </li>
	<?php endif; ?>
</ul>
