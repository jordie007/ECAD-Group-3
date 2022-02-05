<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");

// Import card class
include_once("catalogue/ProductCatalogue.php");

// Echo style
ProductCatalog::echoStyle();
?>

<style>
  .banner {
    background-repeat: no-repeat;
    background-size: cover;
    background-image: url('Images/welcome.jpg');
    background-position: center 38%;
    min-height:30vh;
  }
</style>

<div class="bg-image m-2 text-center shadow-1-strong d-flex justify-content-center align-items-center banner">
    <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
      <div class="d-flex justify-content-center align-items-center h-100">
        <h1 class="text-white mb-0 font-weight-bold font-italic h-100">Take a bite out of hunger</h1>
      </div>
    </div>
</div>

<h2 class="font-weight-bold pt-5">Special Promotions</h2>

<?php
include_once("mysql_conn.php");

$qry = "SELECT * FROM Product p
WHERE Offered = 1";
$result = $conn->query($qry);

// Print product catalogue component
(new ProductCatalog($result, "."))->echoHtml();
?>

<?php
// Include the Page Layout footer
include("footer.php");
?>