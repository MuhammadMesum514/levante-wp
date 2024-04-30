<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi;
global $post;
$rt_post_cat = wp_get_object_terms( $post->ID, 'category', [ 'fields' => 'ids' ] );
// arguments
$args = [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'tax_query'      => [
		[
			'taxonomy' => 'category',
			'field'    => 'id',
			'terms'    => $rt_post_cat,
		],
	],
	'post__not_in'   => [ $post->ID ],
];

$query = new \WP_Query( $args );
get_header();
?>

<main class="site-main content-area blog-grid blog-grid-inner content-area style2 blog">
    <div class="main-post-content">
        <div class="row">
            <div class="col-md-7">
                <div class="section-title-wrapper">
                    <div class="bg-title-wrap">
                        <span class="background-title solid"><?php echo esc_html__( 'Blogs', 'homlisti' ); ?></span>
                    </div>

                    <div class="title-inner-wrapper">
                        <div class="top-sub-title-wrap">
                            <span class="top-sub-title">
                                <i class="fas fa-circle" aria-hidden="true"></i>
                                <?php echo esc_html__( 'What\'s New Trending', 'homlisti' ); ?>
                            </span>
                        </div>
                        <h2 class="main-title"><?php echo esc_html__( 'Related Blogs', 'homlisti' ); ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
			<?php
			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) : $query->the_post();
					get_template_part( 'template-parts/content-alt' );
				endwhile;
			endif;
			?>
        </div>
    </div>
</main>