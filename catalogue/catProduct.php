<?php
session_start(); // STart the current session
// Include the PHP file that establishes database connection handle: $conn
include_once("../mysql_conn.php");
include_once("ProductCatalogue.php");

ProductCatalog::echoStyle();
?>

<?php
$baseURI = ".."; // override; base dir is in parent dir
include("../header.php"); // Include the Page Layout header
?>

<div class="container">
  <!-- Display Page Header - Category's name is queried from the DB -->
  <div class="row px-3">
    <span class="page-title">
      Category – <?php
                  $qry = "SELECT * FROM Category WHERE CategoryID = ?";
                  $stmt = $conn->prepare($qry);
                  $stmt->bind_param("i", $_GET["cid"]);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $stmt->close();

                  echo $result->fetch_assoc()["CatName"];
                  ?>
    </span>
  </div>

  <?php

  $cid = $_GET["cid"];
  $qry = "SELECT *
		FROM CatProduct cp INNER JOIN Product p ON cp.ProductID = p.ProductID
		WHERE cp.CategoryID=?
    ORDER BY p.ProductTitle";
  $stmt = $conn->prepare($qry);
  $stmt->bind_param("i", $cid);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  // Print product catalogue component
  (new ProductCatalog($result))->echoHtml();
  ?>
</div>

<?php
$conn->close(); // Close database connnection
include("../footer.php"); // Include the Page Layout footer
?>