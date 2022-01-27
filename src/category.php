<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<style>
.product:hover {
  text-decoration: none;
}
.product .row{
  background-color: white;
}
.product .row:hover{
  background-color: #ffd566;
}
</style>
<!-- Create a container, 60% width of viewport -->
<div style="width:60%; margin:auto;">
<!-- Display Page Header -->
<div class="row" style="padding:5px"> <!-- Start of header row -->
    <div class="col-12">
        <span class="page-title">Product Categories</span>
        <p>Select a category listed below:</p>
    </div>
</div> <!-- End of header row -->

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// To Do:  Starting ....
$qry = "SELECT * FROM Category";
$result = $conn->query($qry);
while ($row = $result->fetch_array()) {
    $catname = urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    echo "<a href=$catproduct class='product' style='color:black'><div class='row' style='border-radius: 4vh;padding:20px;box-shadow: 0px 0px 10px #C6C6C6;margin-bottom:20px'>";
    echo "<div class='col-md-8'>";
    echo "<p style='font-size: 1.5em;font-weight:700'>$row[CatName]</p>";
    echo "$row[CatDesc]";
    echo "</div>";

    $img = "./Images/category/$row[CatImage]";
    echo "<div class='col-md-4' >";
    echo "<img src='$img' style='position: relative;
            top: 50%;
            transform: translateY(-50%);'>";
    echo "</div>";

    echo "</div></a>";
}
// To Do:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
