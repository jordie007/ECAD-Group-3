<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 

// Reading inputs entered in previous page
$email = strtolower($_POST["email"]);
$pwd = $_POST["password"];
include_once("mysql_conn.php");
// To Do 1 (Practical 2): Validate login credentials with database
$qry = "SELECT * FROM Shopper WHERE Email = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
	// Save user's info in session variables
	$row1 = $result->fetch_array();
	$hashed_pwd = $row1["Password"];
	if (password_verify($pwd,$hashed_pwd)==true){
		$checkLogin = true;		
		$_SESSION["ShopperName"] = $row1["Name"];
		$_SESSION["ShopperID"] = $row1["ShopperID"];
	}
	
	// To Do 2 (Practical 4): Get active shopping cart
	$qry = "SELECT sc.ShopCartID, COUNT(sci.ProductID) 
			AS NumItems FROM ShopCart sc 
			LEFT JOIN ShopCartItem sci on sc.ShopCartID=sci.ShopCartID
			WHERE sc.ShopperID=$_SESSION[ShopperID] AND sc.OrderPlaced=0";
	$result2 = $conn->query($qry);
	$row2 = $result2->fetch_array();
	$_SESSION["Cart"] = $row2["ShopCartID"];
	$_SESSION["NumCartItem"] = $row2["NumItems"];
	// Redirect to home page
	header("Location: index.php");
	exit;
}
else {
	echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
}
$conn->close();
// Include the Page Layout footer
include("footer.php");
?>