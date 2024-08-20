<?php
// api_endpoints.php
add_action('rest_api_init', 'reapi_rest_api_init');

function reapi_rest_api_init()
{
    register_rest_route('reapi/v1', '/export', [
        'methods' => 'GET',
        'callback' => 'reapi_get_property_import_array'
    ]);

    register_rest_route('reapi/v1', '/sync_property', [
        'methods' => 'GET',
        'callback' => 'reapi_get_single_property'
    ]);

    register_rest_route('reapi/v1', '/total_item_table', [
        'methods' => 'GET',
        'callback' => 'total_items'
    ]);

    register_rest_route('reapi/v1', '/update_item_for_job', [
        'methods' => 'GET',
        'callback' => 'item_for_job'
    ]);
}


function reapi_get_property_import_array()
{
    return insert_property_import_array_in_db();
}

function reapi_get_single_property()
{
    return reapi_get_single_property_import();
}

function total_items()
{
    return get_total_items_from_sync_propertys();
}

function item_for_job()
{
    $base_url = get_option('siteurl');
    $endpoint = sprintf("%s/wp-json/reapi/v1/sync_property?q=%d", $base_url, time());
    return file_get_contents($endpoint);
}



