<?php require 'inc/init_session.inc' ?>
<?php
require_once "inc/validate.inc";
// Check if post data exists
if(isset($_POST["firstname"])) {
    $errors = array();
    // validate fields
    validatePatterns($errors, $_POST, 'email', "/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/");
    validatePatterns($errors, $_POST, 'phone', "/^\d{3}(-?)\d{3}(-?)\d{4}$/");
    // if no errors
    if (count($errors) == 0) {
        $data = [
            'firstname' => $_POST["firstname"],
            'lastname' => $_POST["lastname"],
            'password' => $_POST["password"],
            'email' => $_POST["email"],
            'phone' => $_POST["phone"],
            'salt' => bin2hex(random_bytes(20)),
        ];
        // Open connection and insert user into DB
        $pdo = new PDO('mysql:host=localhost;dbname=parking', 'jiangmf', 'password');
        $stmt = $pdo->prepare("INSERT INTO puser
        (first_name, last_name, salt, password, email, phone)
        VALUES (:firstname, :lastname, :salt, SHA2(CONCAT(:password, salt), 0), :email, :phone)");
        $stmt->execute($data);
        // Add message to display on next screen
        $_SESSION['message'] = "Registered successfully! Please Login.";
        // Redirect to search page
        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="en">

<head>
    <title>Rent a Driveway | Sign Up</title>
    <?php include 'inc/head.inc' ?>
    <meta property="og:url" content="https://sandbox.ele.moe/parking/register.php" />
    <?php
    // If there were errors
    if(isset($_POST['firstname']) && count($errors) > 0){ ?>
        <script>
        var post = <?= json_encode($_POST) ?>;
        var perrors = <?= json_encode($errors) ?>;
        $(function(){
            // populate the forms with post data
            for(field in post) {
                $("[name='" + field + "']").val(post[field]);
            }
            // display errors
            $("form [name]").each(function() {
                var name = $(this).attr('name');
                if (name in perrors) {
                    getLabel($(this)).addClass("error");
                    $(this).next("span.error").remove();
                    $(this).after("<span class='error'>" + perrors[name] + "</span>")
                }
            });
        })
        </script>
    <?php } ?>
</head>

<body>
    <?php include 'inc/header.inc' ?>
    <div id="container">
        <h1>Sign Up</h1>
        <div class="row">
            <div class="column half">
                <!-- Sign up form -->
                <form action="register.php" method="post" class="clear" id="registration-form">
                    <div class="row">
                        <div class="field column full">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" id="firstname" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="lastname" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="repeatpassword">Password Again</label>
                            <input type="password" name="repeatpassword" id="repeatpassword" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" pattern="\d{3}(-?)\d{3}(-?)\d{4}" title="Please enter a valid phone number" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field column full">
                            <label for="terms">Terms and Conditions</label>
                            <textarea rows="10"><?php include 'inc/tos.inc' ?></textarea>
                            <input type="checkbox" name="terms" id="terms"> By checking this box, you agree to our Terms and Service conditions
                        </div>
                    </div>
                    <button class="btn btn-primary fl-r" type="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.inc' ?>
</body>

</html>