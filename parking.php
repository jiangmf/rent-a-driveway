<?php require 'inc/login_only.inc' ?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="en">

<head>
    <title>Rent a Driveway | View Listing</title>
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.51.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.51.0/mapbox-gl.css' rel='stylesheet' />
    <?php include 'inc/head.inc' ?>
    <meta property="og:url" content="https://sandbox.ele.moe/parking/parking.php" />
    <script>
        var data = {
            "type": "FeatureCollection",
            "features": [
                <?php 
                    $data = [
                        'id' => $_GET['id']
                    ];
                    // Open connection and get user from DB
                    $pdo = new PDO('mysql:host=localhost;dbname=parking', 'jiangmf', 'password');
                    // Match parking id
                    $stmt = $pdo->prepare("SELECT * FROM parking LEFT JOIN 
                    -- Sub query for ratings
                    (SELECT parking_id, AVG(rating) as rating FROM review GROUP BY parking_id) RATINGS on parking.id = RATINGS.parking_id
                    WHERE id = :id
                    LIMIT 1
                    ");
                    
                    $stmt->execute($data);
                    while ($row = $stmt->fetch()) {
                        $id = $row['id'];
                        $title = json_encode($row['title']);
                        $price = json_encode("$" . $row['price'] . " / day");
                        $lat = $row['latitude'];
                        $long = $row['longitude'];
                        $description = json_encode($row['description']);
                        $puser_id = $row['puser_id'];
                        $rating = $row['rating'] ? $row['rating'] : 0;
                        $stmt2 = $pdo->prepare("SELECT first_name, last_name, email, phone FROM puser WHERE id = ?");
                        $stmt2->execute(array($puser_id));
                        $user = json_encode($stmt2->fetch());
                        $stmt3 = $pdo->prepare("SELECT * FROM review WHERE parking_id = ?");
                        $stmt3->execute(array($id));
                        $reviews = [];
                        while ($row = $stmt3->fetch()) {
                            array_push($reviews, [
                                'title' => $row['title'],
                                'description' => $row['description'],
                                'rating' => $row['rating'],
                            ]);
                        }
                        $reviews = json_encode($reviews);

                        echo "{
                            'type': 'Feature',
                            'properties': {
                                'id': $id,
                                'title': $title,
                                'price': $price,
                                'address': '$lat, $long',
                                'rating': $rating,
                                'description': $description,
                                'img': 'assets/images/parking-1.jpeg',
                                'user': $user,
                                'reviews': $reviews,
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
                ?>
            ]
        }
        $(function(){
            $("#review-form").submit(function(e){
                e.preventDefault();
                var data = {
                    parking_id : new URL(window.location.href).searchParams.get("id"),
                    title : $(this).find("[name='headline']").val(),
                    description : $(this).find("[name='review']").val(),
                    rating : $(this).find("[name='rating']").val(),
                }
                $.ajax({
                    method: "POST",
                    url: "review.php",
                    data: data
                })
                .done(function( msg ) {
                    $("#comments-list").append(`<div class="tile">
                        <div class="tile-content">
                            <h4><span class="rating">${getStars(data.rating)}</span> ${data.title}</h4>
                            <p>${data.description}</p>
                        </div>
                    </div>`);
                });
            })
        })
    </script>
</head>

<body onload="generateParkingMap();generateParkingDetails();">
    <?php include 'inc/header.inc' ?>
    <div id="container">
        <h1></h1>
        <div class="row" id="listing-details">
            <!-- Rating, price, contact information and description -->
            <div class="column half">
                <p class="rating"><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></p>
                <p class="price"></p>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i></li>
                    <li><i class="fas fa-user"></i></li>
                    <li><i class="far fa-envelope"></i></li>
                    <li><i class="fas fa-mobile-alt"></i></li>
                </ul>
                <p class="description"></p>
            </div>
            <!-- Map -->
            <div class="column half">
                <div id='parking-map' class='map'></div>
            </div>
        </div>
        <!-- Image and videos -->
        <h2>Media</h2>
        <div class="row">
            <div class="column quarter">
                <a href="assets/images/parking-1.jpeg">
                    <!-- Smaller image for desktop view as they are now displayed in 4 columns -->
                    <picture>
                        <source media="(min-width: 768px)" srcset="assets/images/thumb/parking-1.jpg">
                        <img src="assets/images/parking-1.jpeg" alt="">
                    </picture>
                </a>
            </div>
            <div class="column quarter">
                <a href="assets/images/parking-2.jpeg">
                    <picture>
                        <source media="(min-width: 768px)" srcset="assets/images/thumb/parking-2.jpg">
                        <img src="assets/images/parking-2.jpeg" alt="">
                    </picture>
                </a>
            </div>
            <div class="column quarter">
                <a href="assets/videos/video.mp4">
                    <video controls>
                        <source src="assets/videos/video.mp4" type="video/mp4">
                        Your browser does not support HTML5 video.
                    </video>
                </a>
            </div>
        </div>
        <!-- User reviews using tiles-->
        <h2>User Reviews</h2>
        <div id="comments-list">
        </div>
        <!-- Add a review form, to be shown/hidden by clicking on a button -->
        <form id="review-form" class="clear">
            <fieldset>
                <h3>Add a Review</h3>
                <div class="row">
                    <div class="field column half">
                        <label for="headline">Headline</label>
                        <input type="text" name="headline" id="headline" required>
                    </div>
                    <div class="field column half">
                        <label for="rating">Rating</label>
                        <input type="number" name="rating" id="rating" required>
                        <span class="rating">
                            <i class='far fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i>
                        </span>
                
                    </div>
                </div>
                <div class="row">
                    <div class="field column full" required>
                        <label for="review">Add your review</label>
                        <textarea name="review" id="review" rows="10"></textarea>
                    </div>
                </div>
            </fieldset>
            <button class="btn btn-primary fl-r" type="submit">Submit</button>
        </form>
    </div>
    <?php include 'inc/footer.inc' ?>
</body>

</html>