<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 



// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

// To Do 1 (Practical 2): Validate login credentials with database
include_once("mysql_conn.php");
$qry="SELECT * from shopper WHERE Email=?";
$stmt=$conn->prepare($qry);
$stmt->bind_param("s",$email);
$stmt->execute();
$result=$stmt->get_result();
$stmt->close();
$loggedIn=FALSE;
if ($result->num_rows>0){
	$row=$result->fetch_array();
	$hashed_pwd=$row["Password"];
		$_SESSION["ShopperName"] = $row["Name"];
		$_SESSION["ShopperID"] = $row["ShopperID"];
		$loggedIn=TRUE;
		// To Do 2 (Practical 4): Get active shopping cart
		$qry2="SELECT a.ShopCartID,SUM(b.Quantity) As ItemCount from ShopCart a LEFT JOIN ShopCartItem b ON a.ShopCartID=b.ShopCartID WHERE a.ShopperID=$_SESSION[ShopperID] AND a.OrderPlaced=0";
		$result2=$conn->query($qry2);
		$row2=$result2->fetch_array();
	$_SESSION["Cart"] = $row2["ShopCartID"];
	$_SESSION["NumCartItem"] = $row2["ItemCount"];
	$conn->close();
}

if ($loggedIn){
	// Redirect to home page
	header("Location: index.php");
	exit;
}
else{
	echo "Invalid login credential";
}
// Include the Page Layout footer
include("footer.php");
?>