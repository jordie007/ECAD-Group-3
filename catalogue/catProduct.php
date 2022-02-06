<?php
session_start(); // STart the current session

$baseURI = ".."; // override; base dir is in parent dir

// Include the PHP file that establishes database connection handle: $conn
include_once("../mysql_conn.php");
include("../header.php"); // Include the Page Layout header
include_once("ProductCatalogue.php");

// Print product catalogue component
ProductCatalog::echoStyle();
?>

<?php


$cid = $_GET["cid"] ?? null;

$qry = "SELECT * FROM Category WHERE CategoryID = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$rowCat = $result->fetch_assoc();
if (!$rowCat) {
?>
  <h4 class="text-danger">No category with the ID <?= $cid ?> was found.</h4>
<?php
  exit;
}


?>

<div class="container">
  <!-- Display Page Header - Category's name is queried from the DB -->
  <div class="row px-3">
    <span class="page-title">
      Category – <?= $rowCat["CatName"]; ?>
    </span>
  </div>

  <?php


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