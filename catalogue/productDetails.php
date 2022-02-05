<?php
session_start(); // Detect the current session

$baseURI = ".."; // override; base dir is in parent dir
include("../header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div class="col-sm-11 mx-auto">

    <?php
    $pid = $_GET["pid"] ?? null; // Read Product ID from query string

    // Include the PHP file that establishes database connection handle: $conn
    include_once("../mysql_conn.php");

    $qry = "SELECT * from Product where ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);     // "i" - integer
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $row = $result->fetch_assoc();
    if (!$row) {
    ?>
        <h4 class="text-danger">No product with the ID <?= $pid ?> was found.</h4>
    <?php
        exit;
    }
    ?>
    <div class='row'>
        <div class='col-sm-12' style='padding:5px'>
            <span class='page-title'><?= $row["ProductTitle"] ?></span>
        </div>
    </div>

    <div class='row'>
        <div class='col-sm-9' style='padding:5px'>
            <p><?= $row["ProductDesc"] ?></p>

            <?php
            $qry = "SELECT s.SpecName, ps.SpecVal FROM ProductSpec ps
                        INNER JOIN Specification s ON ps.SpecID = s.SpecID
                        WHERE ps.ProductID=? ORDER BY ps.Priority";
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $stmt->close();
            ?>
            <h5 class="mb-3">Specification</h5>
            <div>
                <?php
                while ($row2 = $result2->fetch_array()) {
                ?>
                    <div class="row">
                        <p class="col-sm-3 mb-1"><?= $row2["SpecName"] ?></h5>
                        <p class="col-sm-2 mb-1 font-weight-bold"><?= $row2["SpecVal"] ?></p>
                    </div>

                <?php
                }
                ?>
                <div class="row">
                    <p class="col-sm-3 mb-1">Stock left</h5>
                    <p class="col-sm-2 mb-1 font-weight-bold"><?= $row["Quantity"] ?></p>
                </div>
            </div>
        </div>
        <div class='col-sm-3' style='vertical-align:top; padding:5px'>
            <p><img src='<?= $baseURI ?>/Images/products/<?= $row["ProductImage"] ?>'></p>

            Price:<span style='font-weight:bold;color:red;'>
                S$ <?= number_format($row["Price"], 2) ?></span>

            <?php
            $formAction = "$baseURI/cartFunctions.php";
            // Check if user logged in
            if (!isset($_SESSION["ShopperID"])) {
                // redirect to login page if the session variable shopperid is not set
                $thisPage = urlencode($_SERVER["REQUEST_URI"]);
                $formAction = "../login.php?redirect={$thisPage}";
            }

            ?>

            <form action='<?= $formAction ?>' method='post'>
                <input type='hidden' name='action' value='add' />
                <input type='hidden' name='product_id' value='<?= $pid ?>' />
                Quantity: <input type='number' name='quantity' value='1' min='1' max='<?= min(30, $row["Quantity"]); ?>' style='width:40px' required />
                <?php if ($row["Quantity"] <= 0) { ?>
                    <button class="btn btn-danger my-2" type='submit' disabled>Out of stock</button>
                <?php } else { ?>
                    <button class="btn btn-success my-2" type='submit'>Add to Cart</button>
                <?php } ?>
            </form>
        </div>
    </div>
</div>

<?php
$conn->close(); // Close database connnection

include("../footer.php"); // Include the Page Layout footer
?>