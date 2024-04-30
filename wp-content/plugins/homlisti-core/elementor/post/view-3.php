<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

use radiustheme\HomListi\Helper;

$thumb_size = $data['project_thumbnail_size'] ? $data['project_thumbnail_size'] : 'rdtheme-size2';
$args       = [
	'post_type'           => 'post',
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => $data['post_limit'],
	'post_status'         => 'publish',
];
if ( $data['orderby'] ) {
	$args['orderby'] = $data['orderby'];
}
if ( $data['order'] ) {
	$args['order'] = $data['order'];
}

if ( $data['post_source'] == 'by_category' && $data['categories'] ) :
	$args = wp_parse_args(
		[
			'cat' => $data['categories'],
		]
		, $args );
endif;

if ( $data['post_source'] == 'by_tags' && $data['tags'] ) :
	$args = wp_parse_args(
		[
			'tag_slug__in' => $data['tags'],
		]
		, $args );
endif;

if ( $data['post_source'] == 'by_id' && $data['post_id'] ) :
	$post_ids         = explode( ',', $data['post_id'] );
	$args['post__in'] = $post_ids;
endif;

if ( $data['exclude'] ) :
	$excluded_ids         = explode( ',', $data['exclude'] );
	$args['post__not_in'] = $excluded_ids;
endif;


if ( $data['offset'] ) {
	$args['offset'] = $data['offset'];
}

$query               = new \WP_Query( $args );
$gird_column_desktop = ( $data['gird_column_desktop'] ? $data['gird_column_desktop'] : '4' );
$gird_column_tab     = ( $data['gird_column_tab'] ? $data['gird_column_tab'] : '6' );
$gird_column_mobile  = ( $data['gird_column_mobile'] ? $data['gird_column_mobile'] : '6' );

$col_class = "col-lg-{$gird_column_desktop} col-md-{$gird_column_tab} col-sm-{$gird_column_mobile}";
?>
<div class="rt-el-post-wrapper blog-grid  <?php echo esc_attr( $data['layout'] ) ?>">
	<?php if ( $query->have_posts() ) : ?>
        <div class="row">
			<?php while ( $query->have_posts() ) : $query->the_post();
				$has_thumbnail = has_post_thumbnail() ? ' has-thumbnail' : ' has-no-thumbnail'; ?>
                <div class="<?php echo esc_attr( $col_class ); ?>">
                    <div class="blog-box grid-style <?php echo esc_attr( $has_thumbnail ) ?>">
						<?php if ( has_post_thumbnail() && 'visible' === $data['thumbnail_visibility'] ):
							$thumb_url = get_the_post_thumbnail_url( get_the_ID(), $thumb_size );
							?>
                            <div class="post-img <?php echo esc_attr( $data['overlay_type'] ) ?>">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="thumb-bg" style="background-image:url(<?php echo esc_url( $thumb_url ) ?>)">
										<?php echo $data['thumb_overlay_visibility'] ? '<div class="overlay"></div>' : null ?>
                                    </div>
                                </a>
								<?php edit_post_link( 'Edit' ); ?>

								<?php if ( $data['date_visibility'] ) : ?>
                                    <a class="post-category" href="<?php echo esc_url( Helper::get_date_archive_link() ) ?>" rel="bookmark">
										<?php
										if ( 'default' !== $data['p_date_format'] &&  class_exists( 'radiustheme\HomListi\Helper' )) {
											echo Helper::time_elapsed_string();
										} else {
											the_time( get_option( 'date_format' ) );
										}
										?>
                                    </a>
								<?php endif; ?>

                            </div>
						<?php endif; ?>
                        <div class="post-content">

                            <div class="post-meta <?php echo esc_attr( $data['is_dots'] ) ?>">

                                <ul class="list-inline">
									<?php if ( $data['author_visibility'] ) : ?>
                                        <li class="author-meta <?php echo esc_attr( $data['author_avatar'] ) ?>">
                                            <span class="author vcard">
                                                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                                                    <?php
                                                    if ( 'image' == $data['author_avatar'] ) {
	                                                    echo get_avatar( get_the_author_meta( 'user_email' ), 35 );
                                                    } elseif ( 'icon' == $data['author_avatar'] ) {
	                                                    echo '<i aria-hidden="true" class="flaticon-user-1"></i>';
                                                    }
                                                    echo esc_html( get_the_author() );
                                                    ?>
                                                </a>
                                            </span>
                                        </li>
									<?php endif; ?>

									<?php if ( $data['cat_visibility'] ) : ?>
                                        <li class="category-meta">
                                        <span class="posted-in">
                                            <?php echo get_the_category_list( esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'homlisti-core' ) );
                                            ?>
                                        </span>
                                        </li>
									<?php endif; ?>

									<?php if ( $data['reading_time_visibility'] ) : ?>
                                        <li class="reading-time">
                                            <a href="#" data-toggle="tooltip" data-original-title="Reading Time"><?php echo Helper::reading_time_count( get_the_content(), false, $data['reading_suffix'] ); ?></a>
                                        </li>
									<?php endif; ?>

									<?php if ( $data['comment_visibility'] ) : ?>
                                        <li class="comments-meta">
                                            <span class="post-comments-number">
                                                <?php
                                                comments_popup_link(
	                                                esc_html__( 'No Comment', 'homlisti-core' ),
	                                                esc_html__( '1 Comment', 'homlisti-core' ),
	                                                esc_html__( '% Comments', 'homlisti-core' ), '',
	                                                esc_html__( 'Comments are Closed', 'homlisti-core' )
                                                ); ?>
                                            </span>
                                        </li>
									<?php endif; ?>
                                </ul>

                            </div>

                            <<?php echo esc_attr($data['title_tag']) ?> class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo esc_attr($data['title_tag']) ?>>

							<?php if ( 'visible' == $data['content_visibility'] ) : ?>
                                <div class="post-excerpt">
									<?php
									$content = strip_shortcodes( wp_strip_all_tags( get_the_content() ) );
									$content = wp_trim_words( $content, $data['content_limit'], '' );
									echo wp_kses_post( $content );
									?>
                                </div>
							<?php endif; ?>

							<?php if ( $data['author_bottom_visibility'] ): ?>
                                <div class="post-meta">
                                    <ul class="entry-meta post-author">
                                        <li class="author-meta <?php echo esc_attr( $data['author_bottom_avatar'] ) ?>">
                                            <span class="author vcard">
                                                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                                                    <?php
                                                    if ( 'image' == $data['author_bottom_avatar'] ) {
	                                                    echo get_avatar( get_the_author_meta( 'user_email' ), 35 );
                                                    } elseif ( 'icon' == $data['author_bottom_avatar'] ) {
	                                                    echo '<i aria-hidden="true" class="flaticon-user-1"></i>';
                                                    }
                                                    echo esc_html__( "By ", 'homlisti-core' );
                                                    echo esc_html( get_the_author() );
                                                    ?>
                                                </a>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
							<?php endif; ?>

							<?php if ( $data['readmore_visibility'] == 'visible' && $data['readmore_text'] ) : ?>
                                <div class="read-more-btn <?php echo $data['show_btn_icon'] ? 'has-icon' : '' ?>">
                                    <a href="<?php the_permalink(); ?>" class="item-btn">
										<?php echo esc_html( $data['readmore_text'] ); ?>
										<?php if ( $data['show_btn_icon'] ) : ?>
                                            <i class="<?php echo esc_attr( $data['btn_icon']['value'] ) ?>"></i>
										<?php endif; ?>
                                    </a>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
			<?php endwhile; ?>
        </div>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</div>