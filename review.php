<?php require 'inc/init_session.inc' ?>
<?php
require_once "inc/validate.inc";
// Check if post data exists
if(isset($_POST["title"])) {
    $errors = array();
    // if no errors
    if (count($errors) == 0) {
        $data = [
            'parking_id' => $_POST["parking_id"],
            'title' => $_POST["title"],
            'description' => $_POST["description"],
            'rating' => $_POST["rating"],
        ];
        // Open connection and insert user into DB
        $pdo = new PDO('mysql:host=localhost;dbname=parking', 'jiangmf', 'password');
        $stmt = $pdo->prepare("INSERT INTO review
        (parking_id, title, description, rating)
        VALUES (:parking_id, :title, :description, :rating)");
        $success = $stmt->execute($data);
        echo "{'success': '" . $success . "'}";
        exit();
    }
} else {
    echo "{'error': 'invalid post'}";
}
?>