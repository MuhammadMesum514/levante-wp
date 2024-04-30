<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;
$target   = $data['link']['is_external'] ? ' target="_blank"' : '';
$nofollow = $data['link']['nofollow'] ? ' rel="nofollow"' : '';


?>

<div class="rt-pricing-box-wrapper <?php echo esc_attr($data['is_featured']) ?>">
    
    <?php if($data['is_featured'] == 'is-featured') : ?>
        <div class="is-featured">
            <span><?php echo esc_html($data['featured_text']) ?></span>
        </div>
    <?php endif; ?>
    <header>
        <div class="plan-name-wrap">
            <h3 class="plan-name"><?php echo esc_html($data['title']) ?></h3>
        </div>

        <div class="price-wrap">
            <span class="price"><?php echo esc_html($data['price']) ?></span>
            <span class="seperator">/</span>
            <span class="period"><?php echo esc_html($data['period']) ?></span>
        </div>

        <div class="subtitle">
            <?php echo esc_html($data['subtitle']); ?>
        </div>
    </header>

    <hr/>

    <div class="feature-lists">
        <ul>
        <?php 
            foreach($data['list'] as $item) {
                ?>
                    <li class="elementor-repeater-item-<?php echo esc_attr($item['_id']) ?>">
                        <?php \Elementor\Icons_Manager::render_icon( $item['list_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        <span class="list-item"><?php echo esc_html($item['faature_title']) ?></span>
                    </li>
                <?php 
            }
        ?>
        </ul>
    </div>

    <footer>
        <?php printf("<a class='btn btn-primary button-el rt-animation-btn' href='%s' %s %s >%s</a>", $data['link']['url'], $target, $nofollow, $data['btn_text'] ); ?>
    </footer>

    <?php  if('icon' == $data['icon_type'] || 'image' == $data['icon_type']) : ?>
        <div class="icon-holder">
            <?php 
                if('icon' == $data['icon_type']) {
                    \Elementor\Icons_Manager::render_icon( $data['bgicon'], [ 'aria-hidden' => 'true' ] ); 
                } elseif ('image' == $data['icon_type']) {
                    echo wp_get_attachment_image( $data['image']['id'], 'full' );
                } 
            ?>
        </div>
    <?php endif; ?>
</div>
