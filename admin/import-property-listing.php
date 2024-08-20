<?php

function reapi_get_single_property_import()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_propertys';

    $properties = $wpdb->get_results(
        "SELECT id, value FROM $table_name WHERE status = 'panding' ORDER BY id ASC LIMIT 1"
    );
    $serial_id = $properties[0]->id;
    $properties = json_decode($properties[0]->value, true);

    // Extract Property Attributes
    $property_attributes = $properties['@attributes'] ?? [];
    $property_status = $property_attributes['status'] ?? '';
    $property_uniqueID = $properties['uniqueID'] ?? '';
    $property_agentID = $properties['agentID'] ?? '';
    $property_listingID = $properties['listingId'] ?? '';
    $property_depositTaken = $properties['depositTaken'] ?? '';

    // Extract Listing Agent Details
    $property_listingAgent = $properties['listingAgent'] ?? [];
    $property_agentID = $property_listingAgent['agentID'] ?? '';
    $property_agentProfileDisplay = $property_listingAgent['displayAgentProfile'] ?? '';
    $property_agentName = $property_listingAgent['name'] ?? '';
    $property_agentTelephone = $property_listingAgent['telephone'] ?? '';
    $property_agentEmail = $property_listingAgent['email'] ?? '';
    $property_agentPhoto = $property_listingAgent['agentPhoto'] ?? '';

    // Extract Address Details
    $property_address = $properties['address'] ?? [];
    $property_subNumber = $property_address['subNumber'] ?? '';
    $property_streetNumber = $property_address['streetNumber'] ?? '';
    $property_street = $property_address['street'] ?? '';
    $property_suburb = $property_address['suburb'] ?? '';
    $property_state = $property_address['state'] ?? '';
    $property_postcode = $property_address['postcode'] ?? '';
    $property_country = $property_address['country'] ?? '';

    // Extract Category
    $property_category = $properties['category'] ?? '';

    // Extract Date Available
    $property_dateAvailable = $properties['dateAvailable'] ?? '';

    // Extract Rent and Bond
    $property_rent = $properties['rent'] ?? '';
    $property_bond = $properties['bond'] ?? '';

    // Extract Headline and Description
    $property_headline = $properties['headline'] ?? '';
    $property_description = $properties['description'] ?? '';

    // Extract Land and Building Details
    $property_landDetails = $properties['landDetails'] ?? [];
    $property_landAreaUnit = $properties['areaUnit'] ?? '';

    $property_buildingDetails = $properties['buildingDetails'] ?? [];
    $property_buildingArea = $properties['area'] ?? '';

    // Extract Features
    $property_features = $properties['features'] ?? [];
    $property_bedrooms = $property_features['bedrooms'] ?? 0;
    $property_bathrooms = $property_features['bathrooms'] ?? 0;
    $property_carports = $property_features['carports'] ?? 0;
    $property_ensuite = $property_features['ensuite'] ?? 0;
    $property_garages = $property_features['garages'] ?? 0;
    $property_balcony = $property_features['balcony'] ?? 0;
    $property_builtInRobes = $property_features['builtinRobes'] ?? 0;
    $property_dishwasher = $property_features['dishwasher'] ?? 0;
    $property_ductedHeating = $property_features['ductedHeating'] ?? 0;
    $property_ductedCooling = $property_features['ductedCooling'] ?? 0;
    $property_intercom = $property_features['intercom'] ?? 0;
    $property_poolInGround = $property_features['poolInGround'] ?? 0;

    $property_all_features = array_map('ucfirst', array_keys(array_filter([
        'carports' => $property_features['carports'] ?? 0,
        'ensuite' => $property_features['ensuite'] ?? 0,
        'garages' => $property_features['garages'] ?? 0,
        'balcony' => $property_features['balcony'] ?? 0,
        'builtinRobes' => $property_features['builtinRobes'] ?? 0,
        'dishwasher' => $property_features['dishwasher'] ?? 0,
        'ductedHeating' => $property_features['ductedHeating'] ?? 0,
        'ductedCooling' => $property_features['ductedCooling'] ?? 0,
        'intercom' => $property_features['intercom'] ?? 0,
        'poolInGround' => $property_features['poolInGround'] ?? 0,
    ], function ($value) {
        return $value !== 0;
    })));


    // Extract Allowances
    $property_allowances = $properties['allowances'] ?? [];
    $property_petFriendly = $properties['petFriendly'] ?? 'false';
    $property_smokers = $properties['smokers'] ?? 'false';
    $property_furnished = $properties['furnished'] ?? 'false';

    // Extract Images
    $image_url = $properties['objects']['img'][0]['@attributes']['url'] ?? [];

    // Extract Property Images
    $images = []; // Initialize $images as an empty array

    foreach ($properties['objects']['img'] as $image) {
        $url = $image['@attributes']['url'] ?? ''; // Get the image URL, or an empty string if it doesn't exist
        if ($url) {
            $images[] = $url; // Add the image URL to the $images array
        }
    }

    // Define unique ID for the property
    $unique_id = $property_uniqueID; // assuming this is the unique ID from your data

    // Check if the property with this unique ID already exists
    $args = array(
        'post_type' => 'property',
        'meta_query' => array(
            array(
                'key' => '_property_uniqueID',
                'value' => $unique_id,
                'compare' => '='
            ),
        ),
    );

    $existing_property = new WP_Query($args);

    if ($existing_property->have_posts()) {
        // Property exists, update it
        $existing_property->the_post();
        $property_id = get_the_ID();
    } else {
        if ($properties) {
            // Property does not exist, create a new one
            $property_id = wp_insert_post(array(
                'post_title' => $property_headline,
                'post_content' => $property_description,
                'post_status' => 'publish',
                'post_type' => 'property',
            ));
        }

    }

    // Insert or Update Property Attributes and Other Meta Fields
    update_post_meta($property_id, '_property_attributes', json_encode($property_attributes));
    update_post_meta($property_id, 'rental_status', $property_status);
    update_post_meta($property_id, 'tagline', $property_agentName);
    update_post_meta($property_id, 'address', $property_street);
    update_post_meta($property_id, 'price', $property_rent);
    // update_post_meta($property_id, 'price_suffix', $property_priceSuffix);
    update_post_meta($property_id, 'bedrooms', $property_bedrooms);
    update_post_meta($property_id, 'bathrooms', $property_bedrooms);
    update_post_meta($property_id, 'car_park', $property_carports);
    update_post_meta($property_id, 'pet', 0);
    update_post_meta($property_id, 'property_description', $property_description);
    update_post_meta($property_id, 'enquire_button', home_url('contact'));

    update_post_meta($property_id, 'features', $property_all_features);

    update_post_meta($property_id, '_property_uniqueID', $property_uniqueID);
    update_post_meta($property_id, '_property_agentID', $property_agentID);
    update_post_meta($property_id, '_property_listingID', $property_listingID);
    update_post_meta($property_id, '_property_depositTaken', $property_depositTaken);
    update_post_meta($property_id, '_property_agentProfileDisplay', $property_agentProfileDisplay);
    update_post_meta($property_id, '_property_agentName', $property_agentName);
    update_post_meta($property_id, '_property_agentTelephone', $property_agentTelephone);
    update_post_meta($property_id, '_property_agentEmail', $property_agentEmail);
    update_post_meta($property_id, '_property_agentPhoto', $property_agentPhoto);
    update_post_meta($property_id, '_property_subNumber', $property_subNumber);
    update_post_meta($property_id, '_property_streetNumber', $property_streetNumber);
    update_post_meta($property_id, '_property_street', $property_street);
    update_post_meta($property_id, '_property_suburb', $property_suburb);
    update_post_meta($property_id, '_property_state', $property_state);
    update_post_meta($property_id, '_property_postcode', $property_postcode);
    update_post_meta($property_id, '_property_country', $property_country);
    update_post_meta($property_id, '_property_category', $property_category);
    update_post_meta($property_id, '_property_dateAvailable', $property_dateAvailable);
    update_post_meta($property_id, '_property_rent', $property_rent);
    update_post_meta($property_id, '_property_bond', $property_bond);
    update_post_meta($property_id, '_property_headline', $property_headline);
    update_post_meta($property_id, '_property_description', $property_description);
    update_post_meta($property_id, '_property_landDetails', json_encode($property_landDetails));
    update_post_meta($property_id, '_property_landAreaUnit', $property_landAreaUnit);
    update_post_meta($property_id, '_property_buildingDetails', json_encode($property_buildingDetails));
    update_post_meta($property_id, '_property_buildingArea', $property_buildingArea);
    update_post_meta($property_id, '_property_bedrooms', $property_bedrooms);
    update_post_meta($property_id, '_property_bathrooms', $property_bathrooms);
    update_post_meta($property_id, '_property_ensuite', $property_ensuite);
    update_post_meta($property_id, '_property_garages', $property_garages);
    update_post_meta($property_id, '_property_balcony', $property_balcony);
    update_post_meta($property_id, '_property_builtInRobes', $property_builtInRobes);
    update_post_meta($property_id, '_property_dishwasher', $property_dishwasher);
    update_post_meta($property_id, '_property_ductedHeating', $property_ductedHeating);
    update_post_meta($property_id, '_property_ductedCooling', $property_ductedCooling);
    update_post_meta($property_id, '_property_intercom', $property_intercom);
    update_post_meta($property_id, '_property_poolInGround', $property_poolInGround);
    update_post_meta($property_id, '_property_petFriendly', $property_petFriendly);
    update_post_meta($property_id, '_property_smokers', $property_smokers);
    update_post_meta($property_id, '_property_furnished', $property_furnished);


    set_property_images_gallery($property_id, $images);

    // set property featured image and banar image
    set_featured_image_for_proparty($property_id, $image_url);

    // Update the status of the processed Property in your database
    $wpdb->update(
        $table_name,
        ['status' => 'completed'],
        ['id' => $serial_id]
    );
    // Reset post data
    wp_reset_postdata();

    return "Property imported successfully.";
}

function get_total_items_from_sync_propertys()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_propertys';
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'panding'");
    var_dump($total_items);
}

function set_property_images_gallery($property_id, $images)
{
    if (!empty($images) && is_array($images)) {
        $gallery_ids = []; // Initialize an empty array to store gallery IDs

        foreach ($images as $image) {

            // Extract image name
            $image_name = basename($image);

            // Get WordPress upload directory
            $upload_dir = wp_upload_dir();

            // Download the image from URL and save it to the upload directory
            $image_data = file_get_contents($image);

            if ($image_data !== false) {
                $image_file = $upload_dir['path'] . '/' . $image_name;
                file_put_contents($image_file, $image_data);

                // Prepare image data to be attached to the product
                $file_path = $upload_dir['path'] . '/' . $image_name;
                $file_name = basename($file_path);

                // Insert the image as an attachment
                $attachment = [
                    'post_mime_type' => mime_content_type($file_path),
                    'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
                    'post_content' => '',
                    'post_status' => 'inherit',
                ];

                $attach_id = wp_insert_attachment($attachment, $file_path, $property_id);

                // Add the image to the gallery IDs array
                $gallery_ids[] = $attach_id;
            }
        }

        if (!empty($gallery_ids) && is_array($gallery_ids)) {

            // Update the post meta with the unserialized array
            update_post_meta($property_id, 'property_gallery', $gallery_ids);
        }
    }
}


// Set Property Thumbnail function
function set_featured_image_for_proparty($property_id, $image_url)
{
    // Check if image URL is not empty
    if (empty($image_url)) {
        // echo 'Error: Image URL is empty.';
        return false; // Indicate failure
    }

    // Extract image name from URL
    $image_name = basename($image_url);

    // Get WordPress upload directory
    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['path'];
    $upload_url = $upload_dir['url'];

    // Download the image from URL and save it to the upload directory
    $image_data = file_get_contents($image_url);

    if ($image_data !== false) {
        $image_file = $upload_path . '/' . $image_name;
        file_put_contents($image_file, $image_data);

        // Prepare image data to be attached to the product
        $file_path = $upload_path . '/' . $image_name;
        $file_name = basename($file_path);

        // Insert the image as an attachment
        $attachment = [
            'post_mime_type' => mime_content_type($file_path),
            'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
            'post_content' => '',
            'post_status' => 'inherit',
        ];

        // Insert the attachment into the WordPress media library
        $attach_id = wp_insert_attachment($attachment, $file_path, $property_id);

        // Property Banner Image
        update_post_meta($property_id, 'banner_image', $attach_id);

        if (!is_wp_error($attach_id)) {
            // Generate attachment metadata
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attach_id, $file_path);
            wp_update_attachment_metadata($attach_id, $attachment_data);

            // Set the attachment as the product's featured image
            set_post_thumbnail($property_id, $attach_id);


            return true; // Indicate success
        } else {
            // Handle errors
            echo 'Error inserting attachment: ' . $attach_id->get_error_message();
        }
    } else {
        echo 'Error downloading image.';
    }

    return false; // Indicate failure
}


