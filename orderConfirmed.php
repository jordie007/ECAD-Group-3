<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if(isset($_SESSION["OrderID"]) and isset($_SESSION["delivery"]) and isset($_SESSION["total"])) {	
	include_once("mysql_conn.php");
	echo "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
	echo "<p><b>Order Summary</b><br>";
	$qry = "SELECT sci.Name, sci.Quantity FROM shopcartitem sci 
			INNER JOIN shopcart sc ON sci.ShopCartID = sc.ShopCartID 
			INNER JOIN orderdata od ON od.ShopCartID=sc.ShopCartID 
			WHERE OrderID = $_SESSION[OrderID]";
	$result = $conn->query($qry);
	if ($result->num_rows > 0){
		while ($row = $result->fetch_array()){
			echo "$row[Name] x $row[Quantity]<br>";
		}
	}
	echo "Delivery Mode: $_SESSION[delivery]<br>";
	date_default_timezone_set('Asia/Singapore');
	echo "Estimated Delivery Date and time: ";
	if($_SESSION["delivery"] == "Express"){
		echo date("j/n/y g:i a", strtotime("+2 hour"));
	}
	else {
		if (in_array(date("l"), ["Friday"])){
			echo date("j/n/y g:i a", strtotime("+3 day"));
		}
		elseif (in_array(date("l"), ["Saturday"])){
			echo date("j/n/y g:i a", strtotime("+2 day"));
		}
		else echo date("j/n/y g:i a", strtotime("+1 day"));
	}
	echo "<br>Grand Total: $$_SESSION[total]</p>";
	echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
	echo '<a href="index.php">Continue shopping</a></p>';
} 
else header("Location: index.php");

include("footer.php"); // Include the Page Layout footer
?>
