<?php


use Rtcl\Controllers\Hooks\Filters;
use Rtcl\Models\Listing;
use radiustheme\HomListi\Listing_Functions;
use radiustheme\HomListi_Core\YelpReview;

class HomeListi_Api_ActionsHooks
{

    public static function init() {
        add_action('rtcl_rest_listing_form_after_save_or_update', [__CLASS__, 'save_yelp_categories'], 10, 2);
    }

    /**
     * @param Listing $listing
     * @param WP_REST_Request $request
     */
    static function save_yelp_categories($listing, $request) {
        if (!$listing) {
            return;
        }
        if (Listing_Functions::is_enable_yelp_review()) {
            delete_post_meta($listing->get_id(), 'homlisti_yelp_categories');
            $categories = $request->get_param('yelp_categories');
            if (!empty($categories) && is_array($categories)) {
                $yelp_cats = [];
                foreach ($categories as $cat) {
                    if (array_key_exists($cat, YelpReview::yelp_categories())) {
                        $yelp_cats[] = $cat;
                    }
                }
                if (!empty($yelp_cats)) {
                    update_post_meta($listing->get_id(), 'homlisti_yelp_categories', $yelp_cats);
                }
            }
        }
        if (Listing_Functions::is_enable_panorama_view()) {
            if ($request->get_param('panorama_img_deleted') && $old_panorama_img_id = absint(get_post_meta($listing->get_id(), 'homlisti_panorama_img', true))) {
                delete_post_meta($listing->get_id(), 'homlisti_panorama_img');
                wp_delete_attachment($old_panorama_img_id);
            }
            $file = $request->get_file_params();
            if (!empty($file['panorama_img']['name'][0])) {
                $panoramaImg = $file['panorama_img'];
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $image = array(
                    'name' => $panoramaImg['name'][0],
                    'type' => $panoramaImg['type'][0],
                    'tmp_name' => $panoramaImg['tmp_name'][0],
                    'error' => $panoramaImg['error'][0],
                    'size' => $panoramaImg['size'][0]
                );
                Filters::beforeUpload();
                $status = wp_handle_upload($image, ['test_form' => false]);
                Filters::afterUpload();
                if ($status && !isset($status['error'])) {
                    $filename = $status['file'];
                    $filetype = wp_check_filetype(basename($filename));
                    $wp_upload_dir = wp_upload_dir();
                    $attachment = [
                        'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                        'post_mime_type' => $filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    ];
                    $attach_id = wp_insert_attachment($attachment, $filename);
                    if (!is_wp_error($attach_id)) {
                        wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $filename));
                        update_post_meta($listing->get_id(), 'homlisti_panorama_img', $attach_id);
                    }
                }
            }
        }

        if (Listing_Functions::is_enable_floor_plan()) {
            $old_floor_plan = get_post_meta($listing->get_id(), "homlisti_floor_plan", true);
            $old_floor_plan_img_ids = [];
            if (!empty($old_floor_plan) && is_array($old_floor_plan)) {
                foreach ($old_floor_plan as $item) {
                    if ($img_id = absint($item['floor_img'])) {
                        $old_floor_plan_img_ids[] = $img_id;
                    }
                }
            }
            delete_post_meta($listing->get_id(), 'homlisti_floor_plan');
            $raw_floor_plans = $request->get_param('floor_plans');
            if (!empty($raw_floor_plans) && is_array($raw_floor_plans)) {
                $floor_plans = [];
                $img_files = $request->get_file_params();
                $raw_floor_plan_img_ids = [];
                foreach ($raw_floor_plans as $key => $item) {
                    $_item = [
                        'title' => !empty($item['title']) ? sanitize_text_field($item['title']) : '',
                        'description' => !empty($item['description']) ? sanitize_text_field($item['description']) : '',
                        'bed' => !empty($item['bed']) ? sanitize_text_field($item['bed']) : '',
                        'bath' => !empty($item['bath']) ? sanitize_text_field($item['bath']) : '',
                        'size' => !empty($item['size']) ? sanitize_text_field($item['size']) : '',
                        'parking' => !empty($item['parking']) ? sanitize_text_field($item['parking']) : ''
                    ];
                    if (!empty($item['floor_img_id']) && $img_id = absint($item['floor_img_id'])) {
                        $raw_floor_plan_img_ids[] = $img_id;
                        $_item['floor_img'] = $img_id;
                    } else if (!empty($img_files['floor_plan_imgs_' . $key]['name'][0])) {
                        require_once(ABSPATH . 'wp-admin/includes/file.php');
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        $image = array(
                            'name' => $img_files['floor_plan_imgs_' . $key]['name'][0],
                            'type' => $img_files['floor_plan_imgs_' . $key]['type'][0],
                            'tmp_name' => $img_files['floor_plan_imgs_' . $key]['tmp_name'][0],
                            'error' => $img_files['floor_plan_imgs_' . $key]['error'][0],
                            'size' => $img_files['floor_plan_imgs_' . $key]['size'][0]
                        );
                        Filters::beforeUpload();
                        $status = wp_handle_upload($image, ['test_form' => false]);
                        Filters::afterUpload();
                        if ($status && !isset($status['error'])) {
                            $filename = $status['file'];
                            $filetype = wp_check_filetype(basename($filename));
                            $wp_upload_dir = wp_upload_dir();
                            $attachment = [
                                'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                                'post_mime_type' => $filetype['type'],
                                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            ];
                            $attach_id = wp_insert_attachment($attachment, $filename);
                            if (!is_wp_error($attach_id)) {
                                wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $filename));
                                $_item['floor_img'] = $attach_id;
                            }
                        }
                    }
                    $floor_plans[] = $_item;
                }
                if (!empty($old_floor_plan_img_ids)) {
                    foreach ($old_floor_plan_img_ids as $img_id) {
                        if (empty($raw_floor_plan_img_ids) || !in_array($img_id, $raw_floor_plan_img_ids)) {
                            wp_delete_attachment($img_id);
                        }
                    }
                }
                if (!empty($floor_plans)) {
                    update_post_meta($listing->get_id(), "homlisti_floor_plan", $floor_plans);
                }
            }
        }
    }


}