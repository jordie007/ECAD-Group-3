<?php
// Detect the current session
session_start();



// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

// To Do 1 (Practical 2): Validate login credentials with database
include_once("mysql_conn.php");
$qry = "SELECT * from Shopper WHERE Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$loggedIn = FALSE;
if ($result->num_rows > 0) {
	$row = $result->fetch_array();
	$hashed_pwd = $row["Password"];
	$_SESSION["ShopperName"] = $row["Name"];
	$_SESSION["ShopperID"] = $row["ShopperID"];
	$loggedIn = TRUE;
	// To Do 2 (Practical 4): Get active shopping cart
	$qry2 = "SELECT a.ShopCartID,SUM(b.Quantity) As ItemCount from ShopCart a LEFT JOIN ShopCartItem b ON a.ShopCartID=b.ShopCartID WHERE a.ShopperID=$_SESSION[ShopperID] AND a.OrderPlaced=0";
	$result2 = $conn->query($qry2);
	$row2 = $result2->fetch_array();
	$_SESSION["Cart"] = $row2["ShopCartID"];
	$_SESSION["NumCartItem"] = $row2["ItemCount"];
	$conn->close();
}

if ($loggedIn) {
	if (isset($_REQUEST["redirect"])) {
		// Redirect to specified
		header("Location: {$_REQUEST["redirect"]}");
	} else {
		// Redirect to home page
		header("Location: index.php");
	}
} else {
	// Go back to login page but with an error msg
	$pageUri = "login.php?error=true";
	if (isset($_REQUEST["redirect"])) {
		$pageUri .= "&redirect=" . $_REQUEST["redirect"];
	}
	header("Location: {$pageUri}");
}
