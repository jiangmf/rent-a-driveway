<?php require 'inc/login_only.inc' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rent a Driveway | Search Results</title>
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.51.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.51.0/mapbox-gl.css' rel='stylesheet' />
    <?php include 'inc/head.inc' ?>
    <meta property="og:url" content="https://sandbox.ele.moe/parking/results.php" />
    <script>
        var data = {
            "type": "FeatureCollection",
            "features": [
                <?php 
                    require_once "inc/validate.inc";
                    if(isset($_GET["location"])) {
                    $errors = array();
                    // validate fields
                    validatePatterns($errors, $_GET, 'location', "/^-?[0-9]{1,3}(?:\.[0-9]{1,20})?, -?[0-9]{1,3}(?:\.[0-9]{1,20})?$/");
                    // if no errors
                    if (count($errors) == 0) {
                        $data = [
                            'title' => "%" . $_GET["title"] . "%",
                            'longitude' => $_GET["longitude"],
                            'latitude' => $_GET["latitude"],
                            'distance' => ($_GET["distance"] ? $_GET["distance"] : 100), 
                            'fromPrice' => ($_GET["fromPrice"] ? $_GET["fromPrice"] : 0),
                            'toPrice' => ($_GET["toPrice"] ? $_GET["toPrice"] : PHP_FLOAT_MAX),
                            'rating' => ($_GET["rating"] ? $_GET["rating"] : 0),
                        ];
                        // Open connection and get user from DB
                        $pdo = new PDO('mysql:host=localhost;dbname=parking', 'jiangmf', 'password');
                        // Match email and hashed + salted password
                        $stmt = $pdo->prepare("
                            SELECT * FROM 
                                (SELECT * FROM (
                                -- Sub query to calculate distance
                                SELECT *, IFNULL(r,0) as rating,
                                6371 * ACOS(COS(RADIANS(latitude))
                                 * COS(RADIANS(:latitude))
                                 * COS(RADIANS(longitude) - RADIANS(:longitude))
                                 + SIN(RADIANS(latitude))
                                 * SIN(RADIANS(:latitude))) AS d FROM parking LEFT JOIN 
                                 -- Sub query for ratings
                                 (SELECT parking_id, IFNULL(AVG(rating),0) as r FROM review GROUP BY parking_id) RATINGS on parking.id = RATINGS.parking_id
                                ) TMP
                            WHERE d <= :distance
                            AND title LIKE :title
                            AND price >= :fromPrice
                            AND price <= :toPrice
                            ) TMP2
                            WHERE rating >= :rating
                        ");

                        // $stmt->bindValue(":title", $_GET["title"]);
                        
                        if ($stmt->execute($data)) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // echo json_encode($row);
                                $id = $row['id'];
                                $title = json_encode($row['title']);
                                $price = json_encode("$" . $row['price'] . " / day");
                                $lat = $row['latitude'];
                                $long = $row['longitude'];
                                $rating = $row['rating'] ? $row['rating'] : 0;
                                $distance = $row['d'];
                                $address = json_encode(round($lat, 4) . ", " . round($long,4));
                                $description = json_encode($row['description']);
                                echo "{
                                    'type': 'Feature',
                                    'properties': {
                                        'id': $id,
                                        'title': $title,
                                        'price': $price,
                                        'address': $address,
                                        'rating': $rating,
                                        'distance': $distance,
                                        'description': $description,
                                        'img': 'assets/images/parking-1.jpeg'
                                    },
                                    'geometry': {
                                        'type': 'Point',
                                        'coordinates': [
                                            $long,
                                            $lat,
                                        ]
                                    }
                                },\n";
                            }
                        }
                    }
                }
                ?>
            ]
        }
    </script>
</head>

<body onload="generateResultsMap();generateResultsList();">
    <?php include 'inc/header.inc' ?>
    <div id="container">
        <h1>Refine your search</h1>
        <!-- This form will be prepopulated with search criteria -->
        <form class="clear" action="results.php">
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
        <h2>Found <em>0</em> Results</h2>
        <!-- Placeholder Map -->
        <div id='results-map' class='map'></div>
        <!-- List of results using tiles -->
        <div id="results-list"">
        </div>
    </div>
    <?php include 'inc/footer.inc' ?>
</body>

</html>