<?php
// TRUNCATE Table
function real_truncate_table( $table_name ) {
    global $wpdb;
    $wpdb->query( "TRUNCATE TABLE $table_name" );
}

// Insert Property In Database
function insert_property_in_db(){

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.realestate.com.au/oauth/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic MmFiZDcwMjMtZThjMy00OWNlLTgzMTYtYTI4YzgzZjliYWVkOjIzY2I4MDAxLTNkNjgtNDJkYS1hNWY1LTExMDFiYzIwNTkyNA=='
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
     $Authorization = 'Bearer ' . json_decode($response)->access_token;



    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.realestate.com.au/listing/v1/export',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: ' . $Authorization
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}


function insert_property_import_array_in_db() {
    // Call function to get API response
    $api_response = insert_property_in_db();

    // Ensure that the API response is a valid XML string
    $xml = @simplexml_load_string($api_response);
    if ($xml === false) {
        return "Failed to parse XML.";
    }

    // Convert the XML to JSON and then decode it to an associative array
    $api_response = json_encode($xml, JSON_PRETTY_PRINT);
    $properties = json_decode($api_response, true);

    // Check if 'rental' key exists
    if (!array_key_exists('rental', $properties)) {
        return "The 'rental' key does not exist in the API response.";
    }

    // Get global $wpdb object
    global $wpdb;
    $table_name = $wpdb->prefix . 'sync_propertys';

    // Truncate table
    real_truncate_table( $table_name );
    
    // Loop through each property and insert it into the database
    foreach ($properties['rental'] as $property) {
        // Check if 'unique_id' exists in the property data
        if (!isset($property['uniqueID'])) {
            continue; // Skip if unique_id is not present
        }
        
        $unique_id = $property['uniqueID'];
        
        // Check if the unique_id already exists in the database
        $id_exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table_name WHERE unique_id = %s",
                $unique_id
            )
        );

        if ($id_exists) {
            continue; // Skip to the next property if the unique_id already exists
        }

        // Prepare data for insertion
        $data = array(
            'value'      => json_encode($property), // Serialize the value if it's an array or object
            'unique_id'  => $unique_id,
            'status'     => "panding",
        );

        // Define format for data types ('%s' for strings, adjust as needed)
        $format = array('%s', '%s', '%s');

        // Insert property data into the database
        $result = $wpdb->insert($table_name, $data, $format);

        // Check for errors
        if ($result === false) {
            // Return or log the actual error message
            return "Error inserting property data into database: " . $wpdb->last_error;
        }
    }

    return "Properties inserted successfully.";
}
