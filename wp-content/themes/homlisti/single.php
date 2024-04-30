<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;

$has_comment_or_from = '';
if ( ! RDTheme::$options['post_navigation'] ) {
	$has_comment_or_from = ( comments_open() || get_comments_number() ) ? 'has-comment-form' : 'no-comment-form';
}
get_header();
?>
    <section id="primary" class="content-area single-blog rtcl-widget-border-enable rtcl-widget-is-sticky">
        <div class="container">
            <div class="row">
                <div class="<?php Helper::the_layout_class(); ?>">
					<?php while ( have_posts() ) : the_post(); ?>
                        <div class="single-post-wrapper <?php echo esc_attr( $has_comment_or_from ) ?>">
							<?php
							get_template_part( 'template-parts/content-single' );
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							}
							?>
                        </div>
					<?php endwhile; ?>
                </div>
				<?php
				if ( Helper::has_sidebar() ) {
					get_sidebar();
				}
				?>
            </div>

			<?php
			if ( RDTheme::$options['post_details_related_section'] ) {
				get_template_part( 'template-parts/related', 'posts' );
			}
			?>
        </div>
    </section>

<?php
if ( function_exists( '_mc4wp_load_plugin' ) && RDTheme::$options['newsletter_section'] ) {
	get_template_part( 'template-parts/rt', 'newsletter' );
}

get_footer();