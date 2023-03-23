<!DOCTYPE html>
<html>
        <head>
        </head>
        <body>
                <h1>Webscraper</h1>

                <h4>Enter item to scrape for</h4>
                <form method="post">
                        <input type="text" name="inputbox" placeholder="Enter Item">
                        <input type="submit" class="button" name="submitbutton" value="Enter">
                </form>
                <br>

                <?php	
					include('simple_html_dom.php');
					
					$trimmed_string = trim($_POST["inputbox"]);
					$formatted_string = str_replace(' ', '+', $trimmed_string);
					$all_data = array();
					
					$search_results = file_get_html("https://www.ebay.com/sch/i.html?_from=R40&_trksid=p2380057.m570.l1312&_nkw=" . $formatted_string . "&_sacat=0");
					
					$posting_links_ = $search_results ->find('a.s-item__link');
					$posting_links = array_slice($posting_links_, 1);
					
					foreach ($posting_links as $link) {
						$url = $link->href;
						$listing = file_get_html($url);
						
						$location = '';
						$location_elements = $listing->find('span.ux-textspans.ux-textspans--SECONDARY');
						foreach ($location_elements as $element) {
							$text = $element->plaintext;
							if (strpos($text, 'Located in:') !== false) {
								$location = trim(str_replace('Located in:', '', $text));
								break;
							}
						}
						$model = $listing->find('span.ux-textspans.ux-textspans--BOLD', 3)->plaintext;
						$price = $listing->find('div.x-price-primary', 0)->find('span.ux-textspans', 0)->plaintext;
						
						$data = array(
							'model' => $model,
							'price' => $price,
							'location' => $location
						);
						$all_data[] = $data;
		
						print_r($data);
						echo "<br>";
					}
					
					$data_string = serialize($all_data);
					file_put_contents('data_file.txt', $data_string);
					
				?>	
        </body>
</html>


