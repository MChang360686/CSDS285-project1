<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Each location stored as [lat,lon]:
//$locations = [[46.86765753623418,-120.7675575752153]];
$locations = [
    [43.8533,-120.9266], # Washington
    [46.767, -113.973], # West Montana
    [45.907, -108.653], # East Montana
    [46.266, -100.254], # North and South Dakota
    [45.557, -92.673], # Minnesota and Wisconsin
    [41.949, -84.901], # Michigan, Indiana, Ohio
    [40.232, -77.2], # New York to Virginia
    [44.087, -71.987], # New York to Maine
    [38.289, -119.07], # Above LA and most of Nevada
    [40.261, -112.583], # Nevada Idaho Utah
    [42.717, -107.946], # Wyoming
    [42.067, -100.148], # South Datota and Nebraska
    [41.086, -92.53], # Iowa Missouri Illinois
    [36.009, -83.99], # Kentucky Tennessee Georgia
    [33.982,-81.237], # North Carolina to Georgia
    [34.687, -115.874], # SoCal
    [34.426, -110.911], # Arizona
    [36.841, -107.695], # Four Corners
    [36.751, -102.623], # Colorado, Kansas, Texas
    [36.514, -97.372], # Kansas and Oklahoma
    [36.449, -91.047], # Missouri, Arkansas, West Tennessee
    [32.477, -89.323], # Arkansas, Louisiana, to Alabama
    [28.2, -82.769], # Florida
    [32.638, -103.439], # New Mexico
    [32.397, -101.64], # West Texas
    [29.774, -96.185] # South East Texas
];

$search_urls_file_txt = '';

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

    $url_format = 'https://sapi.craigslist.org/web/v8/postings/search/full?batch=' . $areaId . '-0-360-0-0&cc=US&lang=en&lat=' . $search_lat . '&lon=' . $search_lon . '&query={}&searchPath=' . $searchPath . '%2Fsss&search_distance=250';
    $search_urls_file_txt .= $url_format . "\n";
}

// Make the generated-data directory if it doesn't already exist:
if (!file_exists('generated-data')) {
    mkdir('generated-data', 0777, true);
}

// Output to file so that we can persist the scraped info:
file_put_contents('generated-data/search_urls.txt', $search_urls_file_txt);

?>
