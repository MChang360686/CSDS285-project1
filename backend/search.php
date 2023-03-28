<?php

$locations_data = json_decode(file_get_contents('stored-data/locations.json'));

$search_query_text = $_GET['q'];

foreach ($locations_data as $location_data){
    # Construct the URL by inserting the query text between URL parts 1 and 2:
    #   Note: this concatenation was done rather than string formatting to avoid pain from '%'s being valid in the search query.
    $url = $location_data->url_part_1 . rawurlencode($search_query_text) . $location_data->url_part_2;
    #echo $url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Host: sapi.craigslist.org', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36']);
    $json_str = curl_exec($ch);
    curl_close($ch);

    header('Content-Type: application/json');
    echo $json_str;
}

?>
