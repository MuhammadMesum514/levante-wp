<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;
$comments_number = get_comments_number();
$comments_text   = sprintf( '(%s)', number_format_i18n( $comments_number ) );
$has_entry_meta  = RDTheme::$options['post_author_name'] || RDTheme::$options['post_comment_num'] || RDTheme::$options['post_date'] ? true : false;
$footer_class    = RDTheme::$options['post_tag'] && has_tag() && RDTheme::$options['post_social_icon'] && class_exists( 'HomListi_Core' ) ? 'col-md-6 col-sm-12 col-12'
	: 'col-md-12 col-sm-12 col-12';
$has_post_footer = ( RDTheme::$options['post_tag'] && has_tag() ) || ( RDTheme::$options['post_social_icon'] && class_exists( 'HomListi_Core' ) ) ? true : false;
$has_post_social = ( class_exists( 'HomListi_Core' ) && RDTheme::$options['post_social_icon'] );
?>
<div class="single-blog-content block-content">
    <div id="post-<?php the_ID(); ?>" <?php post_class( 'post-each post-each-single' ); ?>>

		<?php if ( has_post_thumbnail() ): ?>
            <div class="blog-img">
				<?php the_post_thumbnail(); ?>
				<?php edit_post_link( 'Edit' ); ?>
            </div>
		<?php endif; ?>

        <div class="blog-content">
			<?php if ( $has_entry_meta ): ?>
                <div class="post-meta rt-theme-post-meta">
                    <ul class="entry-meta">
						<?php if ( RDTheme::$options['post_author_name'] ): ?>
                            <li>
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 30 ); ?>
                                <span class="vcard author">
                                    <?php echo esc_html__( ' by ', 'homlisti' ); ?>
                                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="fn"><?php the_author(); ?></a>
                                </span>
                            </li>
						<?php endif; ?>
						<?php if ( RDTheme::$options['post_date'] ): ?>
                            <li><i class="fas fa-calendar-alt"></i><span class="updated published"><?php the_time( get_option( 'date_format' ) ); ?></span></li>
						<?php endif; ?>
						<?php if ( RDTheme::$options['post_cats'] && has_category() ): ?>
                            <li><?php the_category( ', ' ); ?></li>
						<?php endif; ?>
						<?php if ( RDTheme::$options['post_comment_num'] ): ?>
                            <li><i class="far fa-comments" aria-hidden="true"></i><?php echo esc_html( $comments_text ); ?></li>
						<?php endif; ?>
						<?php if ( RDTheme::$options['post_details_reading_time'] ): ?>
                            <li>
                                <span data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr__( 'Reading Time', 'homlisti' ) ?>"
                                      data-original-title="<?php echo esc_attr__( 'Reading Time', 'homlisti' ) ?>">
                                    <?php echo Helper::reading_time_count( get_the_content(), true ); ?>
                                </span>
                            </li>
						<?php endif; ?>
                    </ul>
                </div>
			<?php endif; ?>

            <!--TODO: Listing Title Condition may be change -->
			<?php if ( RDTheme::$options['breadcrumb_title'] !== 'homlisti-page-title' || ! RDTheme::$options['breadcrumb']) :
				if(RDTheme::$options['breadcrumb'] && RDTheme::$options['breadcrumb_title'] != 'disable'){
					$title_tag = 'h2';
				} else {
					$title_tag = 'h1';
				}
                ?>
                <div class='post-title-wrap'>
                    <<?php echo esc_html($title_tag) ?> class="post-title"><?php the_title(); ?></<?php echo esc_html($title_tag) ?>>
                </div>
			<?php endif; ?>

            <div class="post-details clearfix"><?php the_content(); ?></div>

			<?php wp_link_pages(); ?>

			<?php if ( $has_post_footer ): ?>
                <div class="social-share <?php echo esc_attr( $has_post_social ? '' : 'has-no-share' ) ?>">
                    <div class="row align-items-center">
						<?php if ( class_exists( 'HomListi_Core' ) && RDTheme::$options['post_social_icon'] ): ?>
                            <div class="<?php echo esc_attr( $footer_class ); ?>">
                                <div class="post-social-share-inner">
                                    <span class="social-label">
                                        <?php echo esc_html__( "Share Link:", "homlisti" ) ?>
                                    </span>
									<?php \HomListi_Core::social_share( Helper::post_share_on_social() ); ?>
                                </div>
                            </div>
						<?php endif; ?>

						<?php if ( has_tag() && RDTheme::$options['post_tag'] ): ?>
                            <div class="<?php echo esc_attr( $footer_class ); ?>">
                                <div class="item-tag">
                                    <label><?php esc_html_e( 'Tags:', 'homlisti' ); ?></label>
									<?php echo get_the_term_list( $post->ID, 'post_tag', '', ', ' ); ?>
                                </div>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>

<?php
if ( RDTheme::$options['post_navigation'] ) {
	get_template_part( 'template-parts/content-single-pagination' );
}
?>
<?php if ( RDTheme::$options['post_author_about'] ): ?>
    <div class="blog-author mt-30">
        <div class="widget-box">
            <div class="media">
                <div class="item-img">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 110 ); ?>
                </div>
                <div class="media-body">
                    <h3 class="item-title"><?php the_author_posts_link(); ?></h3>
                    <p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


