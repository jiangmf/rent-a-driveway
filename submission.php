<?php require 'inc/login_only.inc' ?>
<?php
require_once "inc/validate.inc";
// Check if post data exists
if(isset($_POST["title"])) {
    $errors = array();
    // validate fields
    validatePatterns($errors, $_POST, 'longitude', "/^-?[0-9]{1,3}(?:\.[0-9]{1,20})?$/");
    validatePatterns($errors, $_POST, 'latitude', "/^-?[0-9]{1,3}(?:\.[0-9]{1,20})?$/");
    // if no errors
    if (count($errors) == 0) {
        $data = [
            'puser_id' => $_SESSION["user"]["id"],
            'title' => $_POST["title"],
            'latitude' => $_POST["latitude"],
            'longitude' => $_POST["longitude"],
            'price' => (float) $_POST["price"],
            'description' => $_POST["description"],
            'image' => $_POST["media"],
        ];
        // Open connection and insert parking into DB
        $pdo = new PDO('mysql:host=localhost;dbname=parking', 'jiangmf', 'password');
        $stmt = $pdo->prepare("INSERT INTO parking
        (puser_id, title, latitude, longitude, price, description, image)
        VALUES (:puser_id, :title, :latitude, :longitude, :price, :description, :image)");
        $stmt->execute($data);
        // Add message to display on next screen
        $_SESSION['message'] = "Parking spot submitted successfully!";
        // Redirect to search page
        header('Location: search.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rent a Driveway | Submission</title>
    <?php include 'inc/head.inc' ?>
    <meta property="og:url" content="https://sandbox.ele.moe/parking/submission.php" />
</head>

<body>
    <?php include 'inc/header.inc' ?>
    <div id="container">
        <h1>List a Spot</h1>
        <div class="row">
            <div class="column half">
                <!-- List a Spot Form -->
                <form method="post" action="submission.php" class="clear" id="submission-form">
                    <div class="row">
                        <div class="field column full">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                        <label for="price" data-type="currency">Price</label>
                        <input type="number" name="price" id="price">
                    </div>
                    </div>
                    <div class="row">
                        <div class="field column half">
                            <a class="locate-me" onclick="getLocation(submissionFormLocation)"><img src="assets/images/locate.svg" alt=""> Use my Location</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column half">
                            <label for="longitude">Longitude</label>
                            <input type="text" name="longitude" id="longitude" required>
                        </div>
                        <div class="field column half">
                            <label for="latitude">Latitude</label>
                            <input type="text" name="latitude" id="latitude" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" required rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="media">Media</label>
                            <input type="file" name="media" id="media" multiple>
                        </div>
                    </div>
                    <button class="btn btn-primary fl-r" type="submit">List</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.inc' ?>
</body>

</html>