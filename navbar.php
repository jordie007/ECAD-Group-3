<style>
	.nav-link{
		color:black;
	}
    .nav-link:hover{
        color:white;
    }

</style>


<?php
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='$baseURI/register.php' style='color:black;'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='$baseURI/login.php' style='color:black;'>Login</a></li>";

 $numItemCart = 0;

if(isset($_SESSION["ShopperName"])) {
	//To Do 1 (Practical 2) -
    //Display a greeting message, Change Password and logout links
    //after shopper has logged in.
	$content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
                 <a class='nav-link' href='$baseURI/updateProfile.php'>Update Profile</a></li>
                 <li class='nav-item'>
                 <a class='nav-link' href='$baseURI/logout.php'>Logout</a></li>";
	//To Do 2 (Practical 4) -
    //Display number of item in cart
	if (isset($_SESSION["NumCartItem"])){
        $numItemCart= $_SESSION['NumCartItem'];
    }
}
?>
<!-- To Do 3 (Practical 1) -
     Display a navbar which is visible before or after collapsing -->
<nav class="navbar navbar-expand-md" style="background-color: #ffbb00">
    <span class="navbar-text ml-md-2" style="color:#5c4300;max-width:80%">
        <?php echo $content1; ?>
    </span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" class="bi" fill="white" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"></path>
        </svg>
    </button>
</nav>
<!-- To Do 4 (Practical 1) -
     Define a collapsible navbar -->
<nav class="navbar navbar-expand-md" style="background-color: #ffbb00;border-bottom:5px solid #5c4300;padding-bottom:0">
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseURI ?>/catalogue/category.php">Product Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseURI ?>/catalogue/search.php">Product Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseURI ?>/shoppingCart.php"><i class="fas fa fa-shopping-cart fa-lg"></i>  <?php echo $numItemCart; ?></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <?php echo $content2; ?>
        </ul>
    </div>
</nav>
