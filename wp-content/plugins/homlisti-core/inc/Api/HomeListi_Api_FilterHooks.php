<?php

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use radiustheme\HomListi\Listing_Functions;
use radiustheme\HomListi_Core\YelpReview;

class HomeListi_Api_FilterHooks
{
    public static function init() {
        add_filter('rtcl_rest_api_listing_data', [__CLASS__, 'add_custom_filed_to_listing'], 10, 2);
        add_filter('rtcl_rest_api_single_listing_data', [__CLASS__, 'add_single_listing_data'], 10, 2);
        add_filter('rtcl_rest_api_config_data', [__CLASS__, 'homeListi_config']);
    }

    public static function homeListi_config($config) {
        if (Listing_Functions::is_enable_yelp_review()) {
            $general_settings = Functions::get_option('rtcl_general_settings');
            $config['yelp'] = [
                'categories' => YelpReview::yelp_categories(),
                'apiKey' => isset($general_settings['yelp_api_key']) ? $general_settings['yelp_api_key'] : '',
                'limit' => isset($general_settings['yelp_business_limit']) ? $general_settings['yelp_business_limit'] : 3,
                'sortby' => isset($general_settings['business_sort_by']) ? $general_settings['business_sort_by'] : 'rating',
                'radius' => isset($general_settings['yelp_search_radius']) ? $general_settings['yelp_search_radius'] : 2000
            ];
        }
        if (Listing_Functions::is_enable_panorama_view()) {
            $config['panorama'] = true;
        }
        if (Listing_Functions::is_enable_floor_plan()) {
            $config['floor_plane'] = true;
        }

        return $config;
    }

    /**
     * @param array $data
     * @param Listing $listing
     *
     * @return array
     */
    public static function add_single_listing_data($data, $listing) {
        if (!$listing) {
            return $data;
        }
        if (Listing_Functions::is_enable_panorama_view() && $panorama_img_id = get_post_meta($listing->get_id(), 'homlisti_panorama_img', true)) {
            $imgData = wp_get_attachment_image_src($panorama_img_id, 'full');
            $view_url = HOMLIST_CORE_BASE_URL . 'inc/Api/templates/panoroma.php';
            $showControl = Functions::get_option_item('rtcl_general_settings', 'enable_panorama_control', true, 'checkbox');
            $autoLoad = Functions::get_option_item('rtcl_general_settings', 'enable_panorama_autoload', false, 'checkbox');
            $data['panorama'] = [
                'view_url' => add_query_arg([
                    'imgSrc' => $imgData[0]
                ], $view_url),
                'img' => $imgData,
                'img_id' => $panorama_img_id,
                'showControls' => $showControl,
                'autoLoad' => $autoLoad
            ];
        }
        if (Listing_Functions::is_enable_yelp_review()) {
            $yelp_categories = get_post_meta($listing->get_id(), 'homlisti_yelp_categories', true);
            $data['yelp_categories'] = is_array($yelp_categories) ? $yelp_categories : [];
        }

        // Add floor plane
        if (Listing_Functions::is_enable_floor_plan()) {
            $raw_floor_plan = get_post_meta($listing->get_id(), "homlisti_floor_plan", true);
            if (!empty($raw_floor_plan) && is_array($raw_floor_plan)) {
                $floor_plan = [];
                foreach ($raw_floor_plan as $item) {
                    if (!empty($item['floor_img']) && $img_id = absint($item['floor_img'])) {
                        $item['floor_img_id'] = $img_id;
                        $item['floor_img'] = wp_get_attachment_image_url($img_id, 'full');
                    }
                    $floor_plan[] = $item;
                }
                if (!empty($floor_plan)) {
                    $data['floor_plan'] = $floor_plan;
                }
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @param Listing $listing
     *
     * @return array
     */
    public static function add_custom_filed_to_listing($data, $listing) {
        if ($listing) {
            $category_ids = $listing->get_category_ids();
            $category_id = (is_array($category_ids) && !empty($category_ids)) ? end($category_ids) : 0;
            $data['custom_fields'] = class_exists('RtclPro') ? RtclPro\Helpers\Api::get_custom_fields($category_id, $listing->get_id()) : '';
        }


        return $data;
    }
}