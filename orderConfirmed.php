<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if(isset($_SESSION["OrderID"]) and isset($_SESSION["delivery"]) and isset($_SESSION["total"])) {	
	include_once("mysql_conn.php");
	echo "<div class='col-lg-6' style='margin:auto;padding:0;border-radius:25px;box-shadow: 0px 0px 10px #C6C6C6;'>";
	echo "<div style='font-weight:bold;background-color:#ffbb00;padding:20px;
	border-top-left-radius:25px;border-top-right-radius:25px;text-align:center'>
	<p style='color:green;font-size:2em'>Checkout successful</p><p> Order Number: <b>$_SESSION[OrderID]</b></p></div>";
	echo "<div style='background-color:white;padding:20px;
	border-bottom-left-radius:25px;border-bottom-right-radius:25px;'><p style='text-align:center'><b style='font-size:1.25em;margin:0'>Order Summary</b><br>";
	$qry = "SELECT sci.Name, sci.Quantity FROM shopcartitem sci 
			INNER JOIN shopcart sc ON sci.ShopCartID = sc.ShopCartID 
			INNER JOIN orderdata od ON od.ShopCartID=sc.ShopCartID 
			WHERE OrderID = $_SESSION[OrderID]";
	$result = $conn->query($qry);
	if ($result->num_rows > 0){
		while ($row = $result->fetch_array()){
			$qry1 = "SELECT ProductImage FROM Product WHERE ProductTitle = '$row[Name]'";
			$result1 = $conn->query($qry1);
			$row1 = $result1->fetch_array();
			echo "<div class='row'><div class='col-lg-8 d-flex' style='margin:auto'>
			<div><img src='Images/Products/$row1[ProductImage]' style='width:10vh'></div>
			<div class='m-lg-auto my-auto'>$row[Name] x $row[Quantity]</div></div></div>";
		}
	}
	echo "<hr style='border-color:#5c4300; width:100%;'>Delivery Mode: <b style='float:right'>$_SESSION[delivery]</b><br>";
	date_default_timezone_set('Asia/Singapore');
	echo "Estimated Delivery Date and time: <b style='float:right'>";
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
	echo "</b><br>Grand Total: <b style='float:right'>$$_SESSION[total]</b></p> <hr style='border-color:#5c4300; width:100%;'>";
	echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
	echo '<a href="index.php" style="background-color:#ffbb00;text-decoration:none;font-weight:bold;float:right;color:black;padding:10px;border-radius:10px">Continue shopping</a></p>';
	echo "</div></div>";
} 
else header("Location: index.php");

include("footer.php"); // Include the Page Layout footer
?>
