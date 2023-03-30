<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config-loader.php');

# Reads Category ID to Abbreviation map: (from https://stackoverflow.com/a/5164417)
$category_id_to_abbreviation_map = json_decode(file_get_contents('category-id-to-abbreviation.json'), true);

$search_query_text = $_GET['q'];

$command = escapeshellcmd($python_path . ' search-ingest.py "' . rawurlencode($search_query_text) . '"');
$output = shell_exec($command);

$per_location_response = explode("|\n", $output);

$locations_data = [];

foreach ($per_location_response as $raw_location_response){
    $pulled_location_data = json_decode($raw_location_response);

    $basePostingId = $pulled_location_data->data->decode->minPostingId;
    $pulled_items = $pulled_location_data->data->items;

    # Set the maximum items for this location bubble:
    #  (360 appears to be the maximum for the endpoint we're hitting.)
    $max_items_count = 360;

    # Note: If we were being perfect, then we would not cut off extras up front like we are currently here, as this may also lose some items in removing duplicates later. Optimally, we would pull new items over as needed to meet a target count.
    $pulled_items = array_slice($pulled_items, 0, $max_items_count);

    $locations_decode_map = $pulled_location_data->data->decode->locations;

    foreach ($pulled_items as $item_pulled_data){
        $item_data = new stdClass();

        // TODO REFACTOR SPLIT INTO FUNCS:

        $post_id = $item_pulled_data[0] + $basePostingId;
        //$item_data->post_id = $post_id;
        $post_url_name = NULL;
        foreach ($item_pulled_data as $item_field){
            if (is_array($item_field) && $item_field[0] == 6){
                $post_url_name = $item_field[1];
                break;
            }
        }

        $location_pulled_str = $item_pulled_data[4];
        $location_pulled_split = explode('~', $location_pulled_str);


        if (!is_null($post_url_name)){
            $category_id = $item_pulled_data[2];
            $category_abbreviation = $category_id_to_abbreviation_map[strval($category_id)];

            $location_pulled_split2 = explode(':', $location_pulled_split[0]);
            $location_decode_id = $location_pulled_split2[0];
//             echo $location_pulled_str . '||';
//             echo $location_decode_id;
            //echo '    ';
            #echo $locations_decode_map[$location_decode_id];
            $location_decode_obj = array_values($locations_decode_map[$location_decode_id]);
            $location_name = $location_decode_obj[1];
            $url_secondary_location_insert = '';
            if (count($location_decode_obj) > 2){
                $location_secondary_code = $locations_decode_map[$location_decode_id][2];
                $url_secondary_location_insert = $location_secondary_code . '/';
            }

            $item_data->url = 'https://' . $location_name . '.craigslist.org/' . $url_secondary_location_insert . $category_abbreviation . '/d/' . $post_url_name . '/' . $post_id . '.html';
        }

        $item_data->state = "TODO - BEN'S WORKING ON THIS";

        # Note that price may be -1, which means a price is not given.
        $item_data->price = $item_pulled_data[3];

        $item_data->lat = floatval($location_pulled_split[1]);
        $item_data->lon = floatval($location_pulled_split[2]);

        $item_data->title = $item_pulled_data[count($item_pulled_data) - 1];

        $is_already_added = false;
        foreach ($locations_data as $location_item){
            # TODO REFACTOR MAKE A SINGLE FIELD FOR DUPE CHECKING.
            # Note: The reason we check duplicates by Title and Location rather than an ID is because we noticed that Craigslist itself has a problem with duplicate posts that are clearly of the exact same real world item.
            if (
                $item_data->title == $location_item->title &&
                $item_data->lat == $location_item->lat &&
                $item_data->lon == $location_item->lon
            ){
                $is_already_added = true;
                break;
            }
        }

        if (!$is_already_added){
            array_push($locations_data, $item_data);
        }
    }
}

header('Content-Type: application/json');
echo json_encode($locations_data);

?>
