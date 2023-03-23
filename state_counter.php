<?php
	include("project1.php");
	$data_string = file_get_contents('data_file.txt');
	$all_data = unserialize($data_string);
	
	$state_counters = array(
	'Alabama' => 0,
	'Alaska' => 0,
	'Arizona' => 0,
	'Arkansas' => 0,
	'California' => 0,
	'Colorado' => 0,
	'Connecticut' => 0,
	'Delaware' => 0,
	'Florida' => 0,
	'Georgia' => 0,
	'Hawaii' => 0,
	'Idaho' => 0,
	'Illinois' => 0,
	'Indiana' => 0,
	'Iowa' => 0,
	'Kansas' => 0,
	'Kentucky' => 0,
	'Louisiana' => 0,
	'Maine' => 0,
	'Maryland' => 0,
	'Massachusetts' => 0,
	'Michigan' => 0,
	'Minnesota' => 0,
	'Mississippi' => 0,
	'Missouri' => 0,
	'Montana' => 0,
	'Nebraska' => 0,
	'Nevada' => 0,
	'New Hampshire' => 0,
	'New Jersey' => 0,
	'New Mexico' => 0,
	'New York' => 0,
	'North Carolina' => 0,
	'North Dakota' => 0,
	'Ohio' => 0,
	'Oklahoma' => 0,
	'Oregon' => 0,
	'Pennsylvania' => 0,
	'Rhode Island' => 0,
	'South Carolina' => 0,
	'South Dakota' => 0,
	'Tennessee' => 0,
	'Texas' => 0,
	'Utah' => 0,
	'Vermont' => 0,
	'Virginia' => 0,
	'Washington' => 0,
	'West Virginia' => 0,
	'Wisconsin' => 0,
	'Wyoming' => 0
	);

	function extract_state($string) {
		$comma_pos_1 = strpos($string, ',');
		$comma_pos_2 = strpos($string, ',', $comma_pos_1 + 1);

		if ($comma_pos_1 !== false && $comma_pos_2 !== false) {
			$state = trim(substr($string, $comma_pos_1 + 1, $comma_pos_2 - $comma_pos_1 - 1));
			return $state;
		} else {
			return '';
		}
	}

	foreach ($all_data as $listing) {
		$location = $listing['location'];
		$state = extract_state($location); // implement this function to extract the state from the location string
		if (array_key_exists($state, $state_counters)) {
			$state_counters[$state]++;
		}
	}

	// print the results
	foreach ($state_counters as $state => $count) {
		echo "$state: $count<br>";
	}

?>