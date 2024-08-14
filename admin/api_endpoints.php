<?php 
// api_endpoints.php
add_action('rest_api_init', 'reapi_rest_api_init');

function reapi_rest_api_init() {
    register_rest_route('reapi/v1', '/export', [
        'methods' => 'GET',
        'callback' => 'reapi_get_property'
    ]);
}

function reapi_get_property() {
    return insert_property_in_db();
}
