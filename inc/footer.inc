<div id="footer">
    &copy; 2018 Rent a Driveway
</div>
<?php
// display message if set
if (isset($_SESSION['message']) && $_SESSION['message'] !== "")  {?>
	<div id="notification">
	     <?= $_SESSION['message'] ?>
	</div>
<?php
// reset message
	$_SESSION['message'] = "";
}?>
