<?php
/*
Plugin Name: PXXI CRM Connect by Hameed
Plugin URI: https://pk.linkedin.com/in/hameed-ali
Description: Hi, This plugin is created to connect PXXI CRM - real estate to the WordPress website with basic functions
Version: 1.0
Author: Hameed Ali
Author URI: https://pk.linkedin.com/in/hameed-ali
License: GPL2
*/



// Function for PXXI CRM
function fetch_pixxi_data_from_api() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.pixxicrm.ae/api/sync/pixxi/levante?listingType=NEW&name=2BHK%20in%20Jumeirah&propertyType=COMPOUND&sortType=ASC',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

// Function to generate HTML cards from the retrieved data
function generate_cards_from_data($data) {
    $decoded_data = json_decode($data, true);

    if ($decoded_data && isset($decoded_data['data']['list'])) {
        $list = $decoded_data['data']['list'];

        echo '<div class="card-container">';
        foreach ($list as $item) {
            echo '<div class="card">';
            // Display image if available
            if (!empty($item['photos']) && is_array($item['photos'])) {
                echo '<img src="' . $item['photos'][0] . '" alt="Image">';
            }
            // Display other details in the card
            echo '<div class="card-details">';
            echo '<h2>ID: ' . $item['id'] . '</h2>';
            echo '<p>Property ID: ' . $item['propertyId'] . '</p>';
            echo '<p>Price: ' . $item['price'] . '</p>';
            echo '<p>Agent ID: ' . $item['agent']['id'] . '</p>';
            echo '<p>Phone: ' . $item['agent']['phone'] . '</p>';
            echo '<p>Email: ' . $item['agent']['email'] . '</p>';
            echo '</div>'; // Closing card-details
            echo '</div>'; // Closing card
        }
        echo '</div>'; // Closing card-container
    } else {
        echo 'No data found in the Pixxi CRM API response.';
    }
}

// Create a shortcode to display fetched data in cards on the WordPress frontend
function display_pixxi_data_shortcode() {
    $data = fetch_pixxi_data_from_api();

    // Check if data is retrieved successfully
    if ($data) {
        // Display the HTML cards on the frontend
        generate_cards_from_data($data);
    } else {
        // If there's an error fetching data, display an error message
        echo 'Error fetching data from the Pixxi CRM API';
    }
}

// Register the shortcode
add_shortcode('pixxi_api_data', 'display_pixxi_data_shortcode');


?>

<style>
/* CSS for the PXXI API card */
.pxxi-data .card {
  margin: 10px;
  width: 45%;
}

.pxxi-data .card-container {
  display: flex;
  flex-wrap: wrap;
}

.pxxi-data div div img {
  width: 100%;
  height: 250px;
}

.pxxi-data div.card-details {
  padding: 10px 30px;
}

.pxxi-data div div p {
  margin: 0px;
}
</style>