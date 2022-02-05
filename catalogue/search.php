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
    <form class="col-sm-8 mx-auto my-3 d-flex flex-column" name="frmSearch" method="get" action="">
        <div class="form-group row">
            <!-- 2nd row -->
            <label for="query" class="col-sm-3 col-form-label">Product Keyword</label>
            <div class="flex-fill mr-5">
                <input class="form-control" name="query" id="query" type="search" autofocus <?php if ($query) { ?>value="<?= $query ?>" <?php } ?> />
            </div>
            <div>
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div> <!-- End of 2nd row -->
        <div class="form-check row ml-auto">
            <input class="form-check-input" onchange="this.checked ? $('#advancedSearch').show() : $('#advancedSearch').hide()" type="checkbox" name="isAdvanced" id="flexCheckChecked">
            <label class="form-check-label" for="flexCheckChecked">
                Advanced search
            </label>
        </div>

        <div class="col-sm-9 my-3 mx-auto" style="display:none" id="advancedSearch">

            <div class="d-flex py-2">
                <label for="sweetness" class="col-sm-5 col-form-label">Sweetness (0 for any)</label>
                <div class="col-sm-6">
                    <input type="range" class="form-range w-100" min="0" max="5" value="<?= $_REQUEST["sweetness"] ?? 3 ?>" id="sweetnessRange" oninput="sweetnessText.value=sweetnessRange.value">
                    <input class="form-control" id="sweetnessText" type='number' name='sweetness' value='<?= $_REQUEST["sweetness"] ?? 3 ?>' min='0' max='5' oninput="sweetnessRange.value=sweetnessText.value" />
                </div>
            </div>

            <div class="d-flex py-2">
                <label for="maxPrice" class="col-sm-5 col-form-label">Max price (S$)</label>
                <div class="col-sm-6">
                    <input class="form-control" id="maxPrice" min="0" step="0.5" type='number' name='maxPrice' placeholder="e.g. 15.50" value="<?= $_REQUEST["maxPrice"] ?? null ?>" />
                </div>
            </div>
        </div>
    </form>



    <?php

    $isAdvanced = $_REQUEST["isAdvanced"] ?? false;

    // The non-empty search keyword is sent to server
    if (isset($query)) {
        include_once("../mysql_conn.php");



        $qry = "SELECT * FROM Product p
        WHERE LOWER(ProductTitle) LIKE LOWER(?) OR ProductDesc LIKE LOWER(?)";

        $stmt = null;

        if ($isAdvanced) {
            $sweetness = $_REQUEST["sweetness"] ?? null;
            $maxPrice = $_REQUEST["maxPrice"] ?? null;

            if ($sweetness == 0) {
                // sweetness = 0 can be used to mean "Any" sweetness
                $sweetness = null;
            }

            if (empty($maxPrice)) {
                // set to a high value
                $maxPrice = 99999;
            }

            if ($sweetness) {
                $qry = "SELECT * FROM Product p
                    INNER JOIN ProductSpec ps ON p.ProductID = ps.ProductID AND ps.SpecID = 2
                    WHERE (LOWER(ProductTitle) LIKE LOWER(?) OR ProductDesc LIKE LOWER(?))
                    AND ps.SpecVal = ?
                    AND (p.OfferedPrice <= ? OR p.Price <= ?)";

                $stmt = $conn->prepare($qry);

                $key = "%" . $query . "%";
                $stmt->bind_param("sssdd", $key, $key, $sweetness, $maxPrice, $maxPrice);
            } else {
                $qry = "SELECT * FROM Product p
                    WHERE (LOWER(ProductTitle) LIKE LOWER(?) OR ProductDesc LIKE LOWER(?))
                    AND (p.OfferedPrice <= ? OR p.Price <= ?)";

                $stmt = $conn->prepare($qry);

                $key = "%" . $query . "%";
                $stmt->bind_param("ssdd", $key, $key, $maxPrice, $maxPrice);
            }
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
if ($isAdvanced) {
?>
    <script type="text/javascript">
        // On DOM rendered
        $(function() {
            // Check the box
            $("#flexCheckChecked").prop("checked", true);
            // Unhide the advanced search
            $('#advancedSearch').show();
        });
    </script>
<?php
}

include("../footer.php"); // Include the Page Layout footer

?>