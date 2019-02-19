<div id="header">
    <div class="logo">
        <a href="index.php">
            <!-- Small logo for mobile view -->
            <picture>
                <source media="(min-width: 768px)" srcset="assets/images/logo.png">
                <img src="assets/images/logo-m.png" alt="Rent a Driveway">
            </picture>
        </a>
    </div>
    <!-- Navigation Menu -->
    <nav>
        <?php
            if (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] == true)  {?>
                <a href="logout.php">Log Out</a>
            <?php } else {?>
                <a href="login.php">Login</a>
                <a href="register.php">Sign Up</a>
            <?php }
        ?>
        
        <a href="submission.php"><i class="fas fa-plus-square"></i> <span class="sm-hidden">List a Spot</span></a>
        | <a href="search.php"><i class="fas fa-search"></i> <span class="sm-hidden">Search</span></a>
    </nav>
</div>