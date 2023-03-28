<?php

// Each location stored as [lat,lon]:
//$locations = [[46.86765753623418,-120.7675575752153]];
$locations = [
    [43.8533,-120.9266], # Washington
    [46.767, -113.973], # West Montana
]; 

$location_lookups_data = [];

foreach ($locations as $location){
    $lat = $location[0];
    $lon = $location[1];
    $url = 'https://rapi.craigslist.org/web/v8/locations?cc=US&lang=en&lat=' . $lat . '&lon=' . $lon;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Host: rapi.craigslist.org', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36']);
    $json_str = curl_exec($ch);
    curl_close($ch);

    $location_response = json_decode($json_str);
    $location_response_main = $location_response->data->items[0];
    //echo var_dump($location_response_main);
    $areaId = $location_response_main->areaId;
    $city = $location_response_main->city;
    $state = $location_response_main->region;
    $searchPath = str_replace(' ', '-', strtolower($city)) . '-' . strtolower($state);
    $search_lat = round($lat, 4);
    $search_lon = round($lon, 4);

    $location_lookup = new stdClass();
    # Note that the API URL expects an encoded forward slash, which is %2F, however we also want to format this string later, so we use a '%%' here to escape the '%' literal:
    $location_lookup->url_part_1 = 'https://sapi.craigslist.org/web/v8/postings/search/full?batch=' . $areaId . '-0-360-0-0&cc=US&lang=en&lat=' . $search_lat . '&lon=' . $search_lon . '&query=';
    $location_lookup->url_part_2 = '&searchPath=' . $searchPath . '%%2Fsss&search_distance=250';
    array_push($location_lookups_data, $location_lookup);
}

// Output to file so that we can persist the scraped info:
file_put_contents('stored-data/locations.json', json_encode($location_lookups_data));

?>
