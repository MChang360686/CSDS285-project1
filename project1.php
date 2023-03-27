<!DOCTYPE html>
<html>
        <head>
        </head>
        <body>
                <h1>Webscraper</h1>

                <h4>Enter item to scrape for</h4>
 		    <!--Help Popup code-->
                <div class="popup" style="margin-left: 15px font-family: Arial" onclick="hePop()">Click here for Help
                		<span class="popuptext" id="helpPopup">
                                <p style="text-align:left margin-left: 15px">How to use this application</p>
                                <p style="text-align:left margin-left: 15px">1. Enter name of item you wish to purchase from Ebay into input</p>
                                <p style="text-align:left margin-left: 15px">2. Wait</p>
                        </span>
                </div>

                <form method="post">
                        <input type="text" name="inputbox" placeholder="Enter Item">
                        <input type="submit" class="button" name="submitbutton" value="Enter">
                </form>
                <br>

                <?php
					include('simple_html_dom.php');
					
					#formats input and splices it into url
					$trimmed_string = trim($_POST["inputbox"]);
					$formatted_string = str_replace(' ', '+', $trimmed_string);
					$page_num = 1;
					
					while($page_num <= 10){
						$search_results = file_get_html("https://www.ebay.com/sch/i.html?_from=R40&_nkw={$formatted_string}&_sacat=0&_pgn={$page_num}");
					
						#removes the first non-listing link
						$posting_links_ = $search_results ->find('a.s-item__link');
						$posting_links = array_slice($posting_links_, 1);
						
						#loops through the listing urls
						foreach ($posting_links as $link) {
							$url = $link->href;
							$listing = file_get_html($url);
							
							#extracts the location
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
							
							#puts data into objects
							$data = array(
								'model' => $model,
								'price' => $price,
								'location' => $location
							);
			
							print_r($data);
							echo "<br>";
						}
						$page_num++;
					}
                ?>
	  <!--JS goes here-->
        <script>
                function hePop() {
                        var popup = document.getElementById("helpPopup");
                        popup.classList.toggle("show");
                }
        </script>
        </body>
</html>
