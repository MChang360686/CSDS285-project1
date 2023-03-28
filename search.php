<!DOCTYPE html>
<!-- TODO USE BOOTSTRAP OR SOMETHING FOR STYLING -->
<html>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> <!-- JQuery -->
    </head>
    <body>
        <h1>Search all US Craigslist Postings</h1>

        <h4>Enter item to scrape for</h4>
        <!--Help Popup code-->
        <div class="popup" style="margin-left: 15px font-family: Arial" onclick="hePop()">Click here for Help
            <span class="popuptext" id="helpPopup">
                <p style="text-align:left margin-left: 15px">How to use this application</p>
                <p style="text-align:left margin-left: 15px">1. Enter name of item you wish to purchase from Ebay into input</p>
                <p style="text-align:left margin-left: 15px">2. Wait</p>
            </span>
        </div>

        <div>
            <input id="searchInput" type="text" placeholder="Enter Item">
            <button id="searchSubmitButton">Search</button>
        </div>

        <!--JS goes here-->
        <script>
            $("#searchInput").keyup(function(e) {
                if(e.key === "Enter"){
                    submitSearch();
                }
            });

            $("#searchSubmitButton").click(submitSearch);

            function submitSearch(e) {
                const searchText = $("#searchInput").val();
                console.log("Submitted search:", searchText);
                $.post("backend/search.php?q=" + searchText, function(data, status) {
                    console.log("Result:", data, status);
                });
            }

            function hePop() {
                var popup = document.getElementById("helpPopup");
                popup.classList.toggle("show");
            }
        </script>
    </body>
</html>
