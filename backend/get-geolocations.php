<?php
# This script acts as a POST endpoint which takes an array of arrays in the POST body, where each of the subarrays
# is a latitude longitude pair, like [43.6185, -116.337].


$coordinates = json_decode(file_get_contents('php://input'), true);

$lat_lon_coordinate_input_args = '';

foreach ($coordinates as $coordinate){
    $lat_lon_coordinate_input_args .= $coordinate[0] . ' ' . $coordinate[1] . ' ';
}

$command = escapeshellcmd('python get-geolocation-data.py ' . $lat_lon_coordinate_input_args);
$output = shell_exec($command);
echo $output;

?>

