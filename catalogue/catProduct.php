<?php
session_start(); // STart the current session
// Include the PHP file that establishes database connection handle: $conn
include_once("../mysql_conn.php");
?>
<style>
  .product:hover {
    text-decoration: none;
  }

  .product .row {
    background-color: white;
  }

  .product .row:hover {
    background-color: #ffd566;
  }
</style>

<?php
$baseURI = ".."; // override; base dir is in parent dir
include("../header.php"); // Include the Page Layout header
?>

<!-- Create a container, 60% width of viewport -->
<div style='width:60%; margin:auto;'>
  <!-- Display Page Header - Category's name is queried from the DB -->
  <div class="row" style="padding:5px">
    <div class="col-12">
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
  </div>

  <?php


  // To Do:  Starting ....
  $cid = $_GET["cid"];
  $qry = "SELECT
      p.ProductID, p.ProductTitle, p.ProductImage,
      p.Price, p.Quantity
		FROM CatProduct cp INNER JOIN Product p ON cp.ProductID = p.ProductID
		WHERE cp.CategoryID=?
    ORDER BY p.ProductTitle";
  $stmt = $conn->prepare($qry);
  $stmt->bind_param("i", $cid);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  while ($row = $result->fetch_array()) {
    $product = "productDetails.php?pid=$row[ProductID]";
    $formattedPrice = number_format($row["Price"], 2);
    echo "<a href=$product class='product' style='color:black;'>
      <div class='row' style='border-radius: 4vh;padding:20px;box-shadow: 0px 0px 10px #C6C6C6;margin-bottom:20px'>";
    echo "<div class='col-md-9'>";
    echo "<p style='font-size: 1.5em;font-weight:700'>$row[ProductTitle]</p>";
    echo "Price:<span style='font-weight:bold;color:green;'>
    S$ $formattedPrice</span>";
    echo "</div>";

    $img = "$baseURI/Images/products/$row[ProductImage]";
    echo "<div class='col-md-3'>";
    echo "<img src='$img' class='img-fluid' style='max-height:100px;border-radius: 4vh'>";
    echo "</div>";

    echo "</div></a>";
  }
  // To Do:  Ending ....

  $conn->close(); // Close database connnection
  echo "</div>"; // End of container
  include("../footer.php"); // Include the Page Layout footer
  ?>