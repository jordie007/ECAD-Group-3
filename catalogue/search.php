<?php
session_start(); // Detect the current session

$baseURI = ".."; // override; base dir is in parent dir
include("../header.php"); // Include the Page Layout header
include_once("ProductCatalogue.php");

// print style
ProductCatalog::echoStyle();

$query = isset($_REQUEST["query"]) ? trim($_REQUEST["query"]) : null;
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div class="container">
    <!-- Container -->
    <div class="col-sm-9">
        <span class="page-title">Product Search</span>
    </div>
    <form class="col-sm-8 mx-auto my-3" name="frmSearch" method="get" action="">
        <div class="form-group row">
            <!-- 2nd row -->
            <label for="query" class="col-sm-3 col-form-label">Product Keyword</label>
            <div class="col-sm-6">
                <input class="form-control" name="query" id="query" type="search" autofocus <?php if ($query) { ?>value="<?= $query ?>" <?php } ?> />
            </div>
            <div class="col-sm-3">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div> <!-- End of 2nd row -->
    </form>

    <?php
    // The non-empty search keyword is sent to server
    if ($query && $query != "") {
        include_once("../mysql_conn.php");

        $qry = "SELECT * FROM Product
        WHERE LOWER(ProductTitle) LIKE LOWER(?) OR ProductDesc LIKE LOWER(?)";
        $stmt = $conn->prepare($qry);

        $key = "%" . $query . "%";
        $stmt->bind_param("ss", $key, $key);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            // Save user's info in session variables
            $keyword =   $query;
            echo "<p><b>Search results for $keyword</b></p>";

            // Print product catalogue component
            (new ProductCatalog($result))->echoHtml();
        } else {
            echo "<p><b>No available results</b></p>";
        }
    }
    ?>
</div>

<?php
include("../footer.php"); // Include the Page Layout footer
?>