<?php
/**
 * Newsletter section
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="rt-newsletter-wrapper">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 col-12">
                <div class="section-title-wrapper">
                    <div class="title-inner-wrapper">
                        <h2 class="main-title">
                            <?php echo esc_html__('Sign up for newsletter', 'homlisti') ?>
                        </h2>
                        <div class="description">
                            <p><?php echo esc_html__('Get latest news and update', 'homlisti') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-12">
                <?php echo do_shortcode("[mc4wp_form]") ?>
            </div>
        </div>
    </div>
    <img class="bg-img" src="<?php echo esc_url(get_template_directory_uri().'/assets/img/newsletter-bg.png') ?>" alt="<?php esc_attr_e('Newsletter BG', 'homlisti') ?>">
</div>