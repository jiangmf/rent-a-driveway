<?php require 'inc/login_only.inc' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rent a Driveway | Search</title>
    <?php include 'inc/head.inc' ?>
    <meta property="og:url" content="https://sandbox.ele.moe/parking/search.php" />
</head>

<body>
    <?php include 'inc/header.inc' ?>
    <div id="container">
        <h1>Find a parking spot near you</h1>
        <!-- Search form -->
        <form class="clear" action="results.php" method="get">
            <fieldset>
                <div class="row">
                    <div class="field column three-quarters">
                        <label>Location<a class="locate-me fl-r" onclick="getLocation(searchFormLocation)"><img src="assets/images/locate.svg" alt=""> Use my Location</a></label>
                        <input type="text" name="location" id="location" placeholder="Enter a longitude and latitude pair" required>
                        <input type="hidden" name="longitude" id="longitude">
                        <input type="hidden" name="latitude" id="latitude">
                    </div>
                    <div class="field column quarter sm-full">
                        <label for="distance" data-type="distance">Search Within</label>
                        <input type="text" name="distance" id="distance">
                    </div>
                </div>
                <div class="row">
                    <div class="field column quarter">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title">
                    </div>
                    <div class="field column quarter">
                        <label for="rating">Min Rating</label>
                        <select name="rating" id="rating">
                            <option value="0"></option>
                            <option value="1">★</option>
                            <option value="2">★★</option>
                            <option value="3">★★★</option>
                            <option value="4">★★★★</option>
                            <option value="5">★★★★★</option>
                        </select>
                    </div>
                    <div class="field column quarter">
                        <label for="fromPrice" data-type="currency">Min Price</label>
                        <input type="number" name="fromPrice" id="fromPrice">
                    </div>
                    <div class="field column quarter">
                        <label for="toPrice" data-type="currency">Max Price</label>
                        <input type="number" name="toPrice" id="toPrice">
                    </div>
                </div>
            </fieldset>
            <button class="btn btn-primary fl-r" type="submit">Search</button>
        </form>
        <!-- Featured spots 
        <h2>Featured Spots</h2>
        <div class="row">
            <div class="tile column third">
                <a href="parking.php"><img src="assets/images/parking-1.jpeg" alt=""></a>
                <div class="tile-content">
                    <h3><a href="parking.php">2 spots on Sterling - 5 minutes to MAC - 
                    	<span class="price">$5 / day</span></a></h3>
                </div>
            </div>
            <div class="tile column third">
                <a href="parking.php"><img src="assets/images/parking-2.jpeg" alt=""></a>
                <div class="tile-content">
                    <h3><a href="parking.php">Spacious lot on Sanders Blvd - 5 minutes to MAC - 
                    	<span class="price">$100 / Month</span></a></h3>
                </div>
            </div>
            <div class="tile column third">
                <a href="parking.php"><img src="assets/images/parking-3.jpeg" alt=""></a>
                <div class="tile-content">
                    <h3><a href="parking.php">York Blvd / Inchbury - Walk to Dundurn Castle! - 
                    	<span class="price">$5 / day</span></a></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="tile column third">
                <a href="parking.php"><img src="assets/images/parking-4.jpeg" alt=""></a>
                <div class="tile-content">
                    <h3><a href="parking.php">Lorem ipsum dolor sit amet, consectetur - 
                    	<span class="price">$5 / day</span></a></h3>
                </div>
            </div>
            <div class="tile column third">
                <a href="parking.php"><img src="assets/images/parking-5.jpeg" alt=""></a>
                <div class="tile-content">
                    <h3><a href="parking.php">Lorem ipsum dolor sit amet, consectetur - 
                    	<span class="price">$100 / Month</span></a></h3>
                </div>
            </div>
            <div class="tile column third">
                <a href="parking.php"><img src="assets/images/parking-6.jpeg" alt=""></a>
                <div class="tile-content">
                    <h3><a href="parking.php">Lorem ipsum dolor sit amet, consectetur - 
                    	<span class="price">$5 / day</span></a></h3>
                </div>
            </div>
        </div>
        -->
    </div>
    <?php include 'inc/footer.inc' ?>
</body>

</html>