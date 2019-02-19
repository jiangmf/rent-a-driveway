<?php require 'inc/init_session.inc' ?>
<?php
require_once "inc/validate.inc";
// Check if post data exists
$invalidLogin = false;
if(isset($_POST["email"])) {
    $errors = array();
    // validate fields
    validatePatterns($errors, $_POST, 'email', "/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/");
    // if no errors
    if (count($errors) == 0) {
        $data = [
            'email' => $_POST["email"],
            'password' => $_POST["password"],
        ];
        // Open connection and get user from DB
        $pdo = new PDO('mysql:host=localhost;dbname=parking', 'jiangmf', 'password');
        // Match email and hashed + salted password
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone FROM puser
        WHERE email = :email
        AND password = SHA2(CONCAT(:password, salt), 0);
        ");
        $stmt->execute($data);
        $fetch = $stmt->fetch();
        // if user exists, set logged in status to true and redirect
        if (is_array($fetch)){
            $_SESSION['LoggedIn'] = true;
            $_SESSION['user'] = $fetch;
            // Add message to display on next screen
            $_SESSION['message'] = "Logged in successfully!";
            header('Location: search.php');
            exit();
        } else {
            // else set flag to display error
            $invalidLogin = true;
        }
    }
}
?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="en">

<head>
    <title>Rent a Driveway | Log In</title>
    <?php include 'inc/head.inc' ?>
    <meta property="og:url" content="https://parking.ele.moe/login.php" />
</head>

<body>
    <?php include 'inc/header.inc' ?>
    <div id="container">
        <h1>Login</h1>
        <div class="row">
            <div class="column half">
                <!-- Login Form -->
                <form action="login.php" method="post" class="clear" id="login-form">
                    <div class="row">
                        <div class="field column full">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email">
                            <?php 
                                // display login error
                                if($invalidLogin){
                                    echo "<span class='error'>Invalid Login</span>";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password">
                        </div>
                    </div>
                    <button class="btn btn-primary fl-r" type="submit">Log In</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.inc' ?>
</body>

</html>