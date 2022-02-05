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
        <div class="form-check row">
            <input class="form-check-input" data-toggle="collapse" data-target="#advancedSearch" type="checkbox" name="isAdvanced" id="flexCheckChecked">
            <label class="form-check-label" data-toggle="collapse" data-target="#advancedSearch" for="flexCheckChecked">
                Advanced search
            </label>
        </div>

        <div class="collapse col-sm-8 mx-auto my-3" id="advancedSearch">
            <label for="sweetness" class="col-sm-4 col-form-label row">Sweetness</label>
            <div class="col-sm-6 row">
                <input type="range" class="form-range w-100" min="0" max="5" value="3" id="sweetnessRange" oninput="sweetnessText.value=sweetnessRange.value">
                <input id="sweetnessText" type='number' name='sweetness' value='3' min='0' max='5' oninput="sweetnessRange.value=sweetnessText.value" />
            </div>

            <label for="maxPrice" class="col-sm-4 col-form-label row">Max price</label>
            <div class="col-sm-6 row">
                <input id="maxPrice" type='number' name='maxPrice' value='10' />
            </div>
        </div>
    </form>



    <?php
    // The non-empty search keyword is sent to server
    if (true) {
        include_once("../mysql_conn.php");

        $isAdvanced = $_REQUEST["isAdvanced"] ?? false;

        $qry = "SELECT * FROM Product p
        WHERE LOWER(ProductTitle) LIKE LOWER(?) OR ProductDesc LIKE LOWER(?)";

        $stmt = null;

        if ($isAdvanced) {
            $sweetness = $_REQUEST["sweetness"] ?? null;
            $maxPrice = $_REQUEST["maxPrice"] ?? 9999;

            $qry = "SELECT * FROM Product p
                    INNER JOIN ProductSpec ps ON p.ProductID = ps.ProductID AND ps.SpecID = 2
                    WHERE (LOWER(ProductTitle) LIKE LOWER(?) OR ProductDesc LIKE LOWER(?))
                    AND ps.SpecVal = ?
                    AND (p.OfferedPrice <= ? OR p.Price <= ?)
                    ";

            $stmt = $conn->prepare($qry);

            $key = "%" . $query . "%";
            $stmt->bind_param("sssdd", $key, $key, $sweetness, $maxPrice, $maxPrice);
        } else {
            $stmt = $conn->prepare($qry);

            $key = "%" . $query . "%";
            $stmt->bind_param("ss", $key, $key);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            echo "<p><b>Search results for $query</b></p>";

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