<?php

$location_lookups_data = json_decode(file_get_contents('stored-data/locations.json'));

$search_query_text = $_GET['q'];

$locations_data = [];

foreach ($location_lookups_data as $location_lookup){
    # Construct the URL by inserting the query text between URL parts 1 and 2:
    #   Note: this concatenation was done rather than string formatting to avoid pain from '%'s being valid in the search query.
    $url = $location_lookup->url_part_1 . rawurlencode($search_query_text) . $location_lookup->url_part_2;
    #echo $url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Host: sapi.craigslist.org', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36']);
    $pulled_json_str = curl_exec($ch);
    curl_close($ch);

    $pulled_location_data = json_decode($pulled_json_str);

    $basePostingId = $pulled_location_data->data->decode->minPostingId;
    $pulled_items = $pulled_location_data->data->items;
    $max_items_count = 100;
    $pulled_items = array_slice($pulled_items, 0, $max_items_count);

    foreach ($pulled_items as $item_pulled_data){
        $item_data = new stdClass();

        // TODO REFACTOR SPLIT INTO FUNCS:

        $post_id = $item_pulled_data[0] + $basePostingId;
        $post_url_name = NULL;
        foreach ($item_pulled_data as $item_field){
            if (is_array($item_field) && $item_field[0] == 6){
                $post_url_name = $item_field[1];
                break;
            }
        }

        if (!is_null($post_url_name)){
            $item_data->url = "TODO - BEN'S WORKING ON THIS";
        }

        $item_data->state = "TODO - BEN'S WORKING ON THIS";

        $item_data->price = $item_pulled_data[3];

        $location_pulled_str = $item_pulled_data[4];
        $location_pulled_split = explode('~', $location_pulled_str);
        $item_data->lat = floatval($location_pulled_split[1]);
        $item_data->lon = floatval($location_pulled_split[2]);

        $item_data->title = $item_pulled_data[count($item_pulled_data) - 1];

        array_push($locations_data, $item_data);
    }
}

header('Content-Type: application/json');
echo json_encode($locations_data);

?>
