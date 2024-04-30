<?php
namespace radiustheme\HomListi_Core;

use Rtcl\Helpers\Functions;

class YelpReview {

    private $apiKey;
    private $apiHost;
    private $searchPath;
    private $limit;
    private $sortby;
    private $radius;

    public function __construct() {
        $general_settings = Functions::get_option('rtcl_general_settings');

        $this->apiHost = "https://api.yelp.com";
        $this->searchPath = "/v3/businesses/search";
        //$this->apiKey  = "mwRvAVruXeX4Qz7tWRrRA6WHxVXUlJQZwrXsIjolxbxOC7bxAB1fUCrxz0nDD02SgUSeT6XNDc-t0K-Axa2RbgmtBR6a4jyBYb6S7AWnX5plsDDV5Pzefp2t92_-X3Yx";
        $this->apiKey   = isset($general_settings['yelp_api_key']) ? $general_settings['yelp_api_key'] : '';
        $this->limit    = isset($general_settings['yelp_business_limit']) ? $general_settings['yelp_business_limit'] : 3;
        $this->sortby   = isset($general_settings['business_sort_by']) ? $general_settings['business_sort_by'] : 'rating';
        $this->radius   = isset($general_settings['yelp_search_radius']) ? $general_settings['yelp_search_radius'] : 2000;
    }

    private function request($host, $path, $url_params = array()) {
        // Send Yelp API Call
        try {
            $curl = curl_init();
            if (FALSE === $curl)
                throw new \Exception('Failed to initialize');

            $url = $host . $path . "?" . http_build_query($url_params);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,  // Capture response.
                CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer " . $this->apiKey,
                    "cache-control: no-cache",
                ),
            ));

            $response = curl_exec($curl);

            if (FALSE === $response)
                throw new \Exception(curl_error($curl), curl_errno($curl));
            /*$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $http_status)
                throw new \Exception($response, $http_status);*/

            curl_close($curl);
        } catch(\Exception $e) {
            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);
        }

        return $response;
    }

    private function search( $term, $location ) {

        $url_params = [
            'term'      => $term,
            'location'  => $location,
            'limit'     => $this->limit,
            'sort_by'   => $this->sortby,
            'radius'    => $this->radius,
        ];

        return $this->request( $this->apiHost, $this->searchPath, $url_params );
    }

    public function query_api( $term, $location ) {

        $response = json_decode( $this->search( $term, $location ) );

        return isset($response->businesses) ? $response->businesses : [];

    }

    public static function print_yelp_rating( $rating ) {
        $inactive_star = 5 - ceil( $rating );
        for ( $i = 0; $i < floor( $rating ); $i++ ) {
            echo '<i class="fas fa-star"></i>';
        }

        $is_float = explode(".", $rating);

        if ( isset( $is_float[1] ) ) {
            echo '<i class="fas fa-star-half-alt"></i>';
        }
        while ( $inactive_star ) {
            echo '<i class="far fa-star"></i>';
            $inactive_star--;
        }
    }

    /**
     * Set Yelp Category
     *
     * @return mixed|void
     */
    public static function yelp_categories() {
        $categories = [
            'realestate' => [
                'title' => esc_html__('Real Estate', 'homlisti-core'),
                'icon' => 'fas fa-home',
            ],
            'health' => [
                'title' => esc_html__('Health & Medical', 'homlisti-core'),
                'icon' => 'fas fa-heartbeat',
            ],
            'homeservices' => [
                'title' => esc_html__('Home Services', 'homlisti-core'),
                'icon' => 'fas fa-hammer',
            ],
            'restaurants' => [
                'title' => esc_html__('Restaurants', 'homlisti-core'),
                'icon' => 'fas fa-utensils',
            ],
            'education' => [
                'title' => esc_html__('Education', 'homlisti-core'),
                'icon' => 'fas fa-graduation-cap',
            ],
        ];
        return apply_filters( 'rt_yelp_category_list', $categories );
    }

    /**
     * Get Category Title
     *
     * @param $term
     * @return string $title
     */
    public function get_yelp_category_title( $term ) {
        $categories = $this->yelp_categories();
        if (array_key_exists($term, $categories)) {
            $title = $categories[$term]['title'];
        }
        return isset($title) ? $title : '';
    }

    /**
     * Get Category Icon
     *
     * @param $term
     *
     * @return string $iconClass
     */
    public function get_yelp_category_icon($term) {
        $categories = $this->yelp_categories();
        if (array_key_exists($term, $categories)) {
            $iconClass = $categories[$term]['icon'];
        }
        return isset($iconClass) ? $iconClass : '';
    }

}