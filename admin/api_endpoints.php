<?php 
// api_endpoints.php
add_action('rest_api_init', 'reapi_rest_api_init');

function reapi_rest_api_init() {
    register_rest_route('reapi/v1', '/export', [
        'methods' => 'GET',
        'callback' => 'reapi_get_property_import_array'
    ]);

    register_rest_route( 'reapi/v1', '/sync_property', [
        'methods' => 'GET',
        'callback' => 'reapi_get_single_property'
    ]);

    register_rest_route( 'reapi/v1', '/total_item_table', [
        'methods' => 'GET',
        'callback' => 'total_items'
    ]);
}


function reapi_get_property_import_array() {
    return insert_property_import_array_in_db();
}

function reapi_get_single_property() {
    return reapi_get_single_property_import();
}

function total_items() {
    return get_total_items_from_sync_propertys();
}



