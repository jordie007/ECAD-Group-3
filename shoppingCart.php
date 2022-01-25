<?php 
// Include the code that contains shopping cart's functions.
// Current session is detected in "cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");
include("header.php"); // Include the Page Layout header



if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

echo "<div id='myShopCart' style='margin:auto'>"; // Start a container
if (isset($_SESSION["Cart"])) {
	include_once("mysql_conn.php");
	// To Do 1 (Practical 4): 
	// Retrieve from database and display shopping cart in a table
	$qry="SELECT *,(Price*Quantity) AS Total FROM ShopCartItem WHERE ShopCartID=?";
	$stmt=$conn->prepare($qry);
	$stmt->bind_param("i",$_SESSION["Cart"]);
	$stmt->execute();
	$result=$stmt->get_result();
	$stmt->close();

	
	if ($result->num_rows > 0) {
		// To Do 2 (Practical 4): Format and display 
		// the page header and header row of shopping cart page
		echo "<p class='page-title' style='text-align:center'>Shopping Cart</p>";
		$quantity=0;
		$qry2="SELECT SUM(Quantity) AS 'Quantity' FROM ShopCartItem WHERE ShopCartID=?";
		$stmt2=$conn->prepare($qry2);
		$stmt2->bind_param("i",$_SESSION["Cart"]);
		$stmt2->execute();
		$result2=$stmt2->get_result();
		$stmt2->close();
		while ($row2=$result2->fetch_array()) {
			$quantity += $row2["Quantity"];
		}
		echo "<p class='page-title' style='text-align:center'>Number of items in cart: ".$quantity."</p>"; 
		echo "<div class='table-responsive' >"; // Bootstrap responsive table
		echo "<table class='table table-hover'>"; // Start of table
		echo "<thread class='cart-header'>";
		echo "<tr>";
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<th width='120px'>Total</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>";
		echo "</thread>";
		// To Do 5 (Practical 5):
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"]=array();	
		// To Do 3 (Practical 4): 
		// Display the shopping cart content
		$subTotal = 0; // Declare a variable to compute subtotal before tax,discount,shipping
		$subTotalaDiscount=0;
		echo "<tbody>"; // Start of table's body section
		while ($row=$result->fetch_array()) {
				echo "<tr>";
				echo "<td style='width:50%'>$row[Name]<br/>";
				echo "Product ID: $row[ProductID]</td>";
				$itemprice=$row["Price"];
				
				
				//Get discounted price if any
				$itemqry="SELECT OfferedPrice FROM Product WHERE ProductID=? AND Offered=1 AND NOW() BETWEEN Product.OfferStartDate AND Product.OfferEndDate";
				$stmt3=$conn->prepare($itemqry);
				$stmt3->bind_param("i",$row["ProductID"]);
				$stmt3->execute();
				$itemresult=$stmt3->get_result();
				$stmt3->close();
				while ($itemrow=$itemresult->fetch_array()) {
					$itemprice = $itemrow["OfferedPrice"];
				}

				$formattedoldPrice=number_format($row["Price"],2);
				$formattedPrice=number_format($itemprice,2);
				if ($itemprice==$row["Price"]){
					echo "<td>$formattedPrice</td>";
				}
				else{
					echo "<td><del>$formattedoldPrice</del><br/>$formattedPrice</td>";
				}
				//echo "<td>$formattedPrice</td>";
				echo "<td>";
				echo "<form action='cartFunctions.php' method='post'>";
				echo "<select name='quantity' onChange='this.form.submit()'>";
				for ($i=1; $i<=100;$i++){
					if ($i==$row["Quantity"]){
						$selected="selected";
					}
					else{
						$selected="";
					}
					echo "<option value='$i' $selected>$i</option>";
				}
				echo "</select>";
				echo "<input type='hidden' name='action' value='update' />";
				echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
				echo "</form>";
				echo "</td>";
				$totalprice=$row["Quantity"]*$itemprice;
				$formattedTotal=number_format($totalprice,2);
				//$formattedTotal=number_format($row["Total"],2);
				echo "<td>S$$formattedTotal</td>";
				echo "<td>";
				echo "<form action='cartFunctions.php' method='post'>";
				echo "<input type='hidden' name='action' value='remove' />";
				echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
				echo "<input type='image' src='images/trash-can.png' title='Remove item' />";
				echo "</form>";
				echo "</td>";
				echo "</tr>";
			// To Do 6 (Practical 5):
		    // Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][]=array("productId"=>$row["ProductID"],
			"name"=>$row["Name"],"price"=>$itemprice,"quantity"=>$row["Quantity"]);	
			// Accumulate the running sub-total
			$subTotalaDiscount +=$totalprice;
			$subTotal+=$row["Quantity"]*$row["Price"];
		}
		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table

		/*echo "<form action='cartFunctions.php' method='post'>";
		echo "<p style='text-align:right; font-size:20px'>
		Delivery Method ";
		echo "<select name='deliveryMethod'>";
			echo "<option value='Normal'>Normal</option>";
			echo "<option value='Express'>Express</option>";
		echo "</select>";
		echo "</p></form>";
		$shippingCharge=0;
		$selectedDel = $_POST['deliveryMethod'];  
		if ($selectedDel=='Normal'){
			if ($subTotal>=300){
				$shippingCharge=20;
			}
			else{
				$shippingCharge=30;
			}
		}*/
		// To Do 4 (Practical 4): 
		// Display the subtotal at the end of the shopping cart

		$shippingCharge=0;
		if ($subTotalaDiscount<50){
			$shippingCharge=2;
		}

		$discountedPrice=$subTotal-$subTotalaDiscount;
		if ($discountedPrice!=0){
			echo "<p style='text-align:right; font-size:15px'>Item Discount = -S$" . number_format($discountedPrice,2);
		}
		

		echo "<p style='text-align:right; font-size:20px'>
		Subtotal = S$" . number_format($subTotalaDiscount,2);
		$_SESSION["Total"]=round($subTotalaDiscount,2);	
		// To Do 7 (Practical 5):
		// Add PayPal Checkout button on the shopping cart page
		echo "<p style='text-align:right; font-size:18px'>
		Shipping Charge = S$" . number_format($shippingCharge,2);
		echo "<form method='post' action='checkoutProcess.php'>";
		echo "<input type='image' style='float:right;' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		echo "</form></p>";	
	}
	else {
		echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection
}
else {
	echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
