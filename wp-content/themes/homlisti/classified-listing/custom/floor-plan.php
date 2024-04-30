<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

use radiustheme\HomListi\Listing_Functions;
use Rtcl\Helpers\Functions;

global $listing;

$floorList = get_post_meta( $listing->get_id(), "homlisti_floor_plan", true );

$generalSettings = Functions::get_option( 'rtcl_general_settings' );
$text            = ! empty( $generalSettings['floor_plan_section_label'] ) ? $generalSettings['floor_plan_section_label'] : '';

if(is_array($floorList) && count($floorList) == 1 && (empty($floorList[0]['title']) || empty($floorList[0]['floor_img']))){
    return;
}
?>

<?php if ( ! empty( $floorList ) && Listing_Functions::is_enable_floor_plan() ) { ?>
    <div class="product-plan widget" id="floor-plan">
        <div class="item-heading">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="heading-title"><?php echo esc_html( $text ); ?></h2>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionExample">
			<?php
			$count = 0;
			?>
			<?php foreach ( $floorList as $floor ):
				$count ++;


				$title   = $floor['title'] ?? '';
				$desc    = $floor['description'] ?? '';
				$bed     = ltrim( $floor['bed'], '0' ); //strlen($floor['bed']) == 1 ? '0'.$floor['bed'] : '';
				$bath    = ltrim( $floor['bath'], '0' ); //strlen($floor['bath']) == 1 ? '0'.$floor['bath'] : '';
				$size    = $floor['size'] ?? '';
				$parking = $floor['parking'] ?? '';
				$imgID   = $floor['floor_img'] ?? '';
				if ( $count === 1 ) {
					$show      = 'show';
					$expand    = 'true';
					$collapsed = '';
				} else {
					$show      = '';
					$expand    = 'false';
					$collapsed = ' collapsed';
				}
				?>
                <div class="card">
                    <div class="card-header<?php echo esc_attr( $collapsed ); ?>" data-toggle="collapse"
                         data-target="#collapse<?php echo esc_attr( $count ); ?>"
                         aria-expanded="<?php echo esc_attr( $expand ); ?>" role="tabpanel">
						<?php if ( ! empty( $title ) ): ?>
                            <div class="floor-name"><?php echo esc_html( $title ); ?></div>
						<?php endif; ?>
                        <ul class="entry-meta">
							<?php if ( ! empty( $bed ) ):

								$bed_label = ( $bed == 1 ) ? __( "Bed", "homlisti" ) : __( "Beds", "homlisti" );

								if ( $bed < 10 ) {
									$bed = "0" . $bed;
								}
								?>
                                <li class="d-none d-md-flex">
                                    <i class="flaticon-bed"></i>
                                    <span class='label'>
                                        <?php echo esc_html( $bed_label ) ?>
                                    </span>
                                    <span class='value'><?php echo esc_html( $bed ); ?></span>
                                </li>
							<?php endif; ?>

							<?php if ( ! empty( $bath ) ):

								$bath_label = ( $bath == 1 ) ? __( "Bath", "homlisti" ) : __( "Baths", "homlisti" );

								if ( $bath < 10 ) {
									$bath = "0" . $bath;
								}
								?>
                                <li>
                                    <i class="flaticon-shower"></i>
									<?php
									printf( "<span class='label'>%s</span><span class='value'>%s</span>",
										esc_html( $bath_label ),
										esc_html( $bath )
									);
									?>
                                </li>
							<?php endif; ?>

							<?php if ( ! empty( $size ) ): ?>
                                <li>
                                    <i class="flaticon-full-size d-none d-md-inline-block"></i>
									<?php echo esc_html( $size ); ?>
                                </li>
							<?php endif; ?>
                        </ul>
                    </div>

                    <div id="collapse<?php echo esc_attr( $count ); ?>"
                         class="collapse <?php echo esc_attr( $show ); ?> tab-content" data-parent="#accordionExample">
                        <div class="card-body">
							<?php if ( ! empty( $desc ) ): ?>
                                <p><?php echo esc_html( $desc ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $imgID ) ): ?>
                                <div class="floor-design blocks-gallery-item">
                                    <a href="<?php echo esc_url( wp_get_attachment_image_url( $imgID, 'full' ) ); ?>">
										<?php echo wp_get_attachment_image( $imgID, 'full' ); ?>
                                    </a>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
    </div>
<?php } ?>