<!DOCTYPE html>
<html>
        <head>
        </head>
        <body>
                <h1>Webscraper</h1>

                <h4>Enter a URL to scrape for data</h4>
                <form method="post">
                        <input type="text" name="inputbox" placeholder="Enter URL">
                        <input type="submit" class="button" name="submitbutton" value="Enter">
                </form>
                <br>

                <?php
                        $websitetobescraped = $_POST['inputbox'];
                        $code = file_get_contents($websitetobescraped);
                ?>
                <textarea name="displayarea" cols='80' rows='40'><?php echo $code ?></textarea>

        </body>
</html>