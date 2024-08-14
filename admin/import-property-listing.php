<?php
function fetch_data_from_api()
{

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
            'Authorization: Bearer b2adf65f-00e8-420e-a950-a04b9907381b'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}


// Insert Property In Database
function insert_property_in_db()
{
    $api_response = fetch_data_from_api();
    $xmlData = <<<XML
    $api_response
    XML;

    $xml = simplexml_load_string($xmlData);
    $property = json_encode($xml, JSON_PRETTY_PRINT);

    // json decode
    $property = json_decode($property, true);


}