<style>
	.button1{
		border:none;
		padding:10px;
		font-size:15px;
	}
	.button1:hover{
		color:white;
		background-color:#f44336
	}

	.checkout-btn{
		width:100%;
		height:40px;
		padding:5px;
		font-weight:800;
		background-color:#FFC36E;
		border-radius:5px;
	}
	.checkout-btn:hover{
		background-color:#FEAC38;
		color:white;
	}

	.tblContent{
		padding-right:10px;
		padding-top: 5px;
		padding-bottom:5px;
		font-weight:600;
	}
	.tblval{
		float:right;
		padding-top: 5px;
		padding-bottom:5px;
	}

	.selectDel{
		height:25px;
		background-color:#FFDEAF;
	}



</style>
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

if (isset($_SESSION["Error"])){
	echo "<script>alert('$_SESSION[Error]')</script>";
	unset($_SESSION["Error"]);
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
		echo "<th style='width:10%;'></th>";
		echo "<th style='width:40%;'>Item</th>";
		echo "<th style='width:20%;'>Price</th>";
		echo "<th style='width:20%;'>Quantity</th>";
		echo "<th style='width:10%;'>Total</th>";
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
				echo "<tr style='font-size:25px;'>";
				echo "<td style='width:10%;'>";
				$imgqry="SELECT ProductTitle,ProductImage FROM Product WHERE ProductID=?";
				$imgstmt=$conn->prepare($imgqry);
				$imgstmt->bind_param("i",$row["ProductID"]);
				$imgstmt->execute();
				$imgresult=$imgstmt->get_result();
				$imgstmt->close();
				$imgsrc="";
				$imgname="";
				while ($imgrow=$imgresult->fetch_array()) {
					$imgname=$imgrow["ProductTitle"];
					$imgsrc='Images/Products/'.$imgrow["ProductImage"];
				}
				echo "<img src='$imgsrc' alt='$imgsrc' style='width:100px;height:100px;'>";
				echo "</td>";

				echo "<td style='width:40%;'>$row[Name]<br/>";
				echo "<form action='cartFunctions.php' method='post'>";
				echo "<input type='hidden' name='action' value='remove' />";
				echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
				echo "<input type='hidden' name='prod_quantity' value='$row[Quantity]' />";
				echo "<input type='submit' class='button1' value='Remove item'/>";
				echo "</form></td>";
				//echo "Product ID: $row[ProductID]</td>";
				$itemprice=$row["Price"];
				
				
				//Get discounted price if any
				$itemqry="SELECT OfferedPrice FROM Product WHERE ProductID=? AND Offered=1 AND NOW() BETWEEN Product.OfferStartDate AND Product.OfferEndDate";
				$stmt3=$conn->prepare($itemqry);
				$stmt3->bind_param("i",$row["ProductID"]);
				$stmt3->execute();
				$itemresult=$stmt3->get_result();
				$stmt3->close();
				while ($itemrow=$itemresult->fetch_array()) {
					if ($itemrow["OfferedPrice"]<$itemprice){
						$itemprice = $itemrow["OfferedPrice"];
					}
					
				}

				$formattedoldPrice=number_format($row["Price"],2);
				$formattedPrice=number_format($itemprice,2);
				if ($itemprice==$row["Price"]){
					echo "<td style='font-size:20px;'>$$formattedPrice</td>";
				}
				else{
					echo "<td><del style='font-size:20px;'>$$formattedoldPrice</del><br/><b>$$formattedPrice</b></td>";
				}
				//echo "<td>$formattedPrice</td>";
				echo "<td style='font-size:20px;'>";
				echo "<form action='cartFunctions.php' method='post'>";
				/*echo "<select name='quantity' onChange='this.form.submit()'>";
				for ($i=1; $i<=100;$i++){
					if ($i==$row["Quantity"]){
						$selected="selected";
					}
					else{
						$selected="";
					}
					echo "<option value='$i' $selected>$i</option>";
				}
				echo "</select>";*/
				echo "<input type='number' name='quantity' style='width:55px;' onChange='this.form.submit()' value=$row[Quantity] min='0' max='100'>";
				echo "<input type='hidden' name='action' value='update' />";
				echo "<input type='hidden' name='prod_quantity' value='$row[Quantity]' />";
				echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
				echo "</form>";
				echo "</td>";
				$totalprice=$row["Quantity"]*$itemprice;
				$formattedTotal=number_format($totalprice,2);
				//$formattedTotal=number_format($row["Total"],2);
				echo "<td>S$$formattedTotal</td>";
				/*echo "<td>";
				echo "<form action='cartFunctions.php' method='post'>";
				echo "<input type='hidden' name='action' value='remove' />";
				echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
				echo "<input type='image' src='images/trash-can.png' title='Remove item' />";
				echo "</form>";
				echo "</td>";*/
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

		/*
		
		echo "<form method='post' action='cartFunctions.php' name='deliveryForm'>";
		echo "<div style='text-align:right'>
			<input type='hidden' name='action' value='shipping' />
			<label for='delivery' style='text-align:right; font-size:20px'>Delivery Mode: </label>
			<select id='delivery' class='selectDel' name='delivery' onchange='deliveryForm.submit();'>";
		echo "<option value='Normal'>Normal Delivery ($2) - 1 working day</option>";
		if (isset($_SESSION['delivery'])){
			if($_SESSION['delivery'] != "Normal")
				echo "<option value='Express' selected>Express Delivery ($5) - 2 hours</option>";
			else echo "<option value='Express'>Express Delivery ($5) - 2 hours</option>";
		}
		else {
			$_SESSION['delivery'] = "Normal";
			echo "<option value='Express'>Express Delivery ($5) - 2 hours</option>"; 
		}
		echo "</select></div></form>";
		*/
		
		
		
		
		echo "<table style='float:right;margin-bottom:20px;font-size:20px;'>";
		
		
		//$discountedPrice=$subTotal-$subTotalaDiscount;


		/*if ($discountedPrice!=0){
			echo "<tr><td class='tblContent'>Item Discount: </td><td class='tblval'> -S$".number_format($discountedPrice,2)."</td></tr>";
		}*/

		echo "<tr><td class='tblContent' style='vertical-align:middle;'>Delivery Mode: </td>";
		echo "<td class='tblval'>";
		
		echo "<form method='post' action='cartFunctions.php' name='deliveryForm'>";
		echo "<div style='text-align:right'>
			<input type='hidden' name='action' value='shipping' />
			<select class='selectDel' id='delivery' name='delivery' onchange='deliveryForm.submit();'>";
		echo "<option value='Normal'>Normal Delivery ($2) - 1 working day</option>";
		if (isset($_SESSION['delivery'])){
			if($_SESSION['delivery'] != "Normal")
				echo "<option value='Express' selected>Express Delivery ($5) - 2 hours</option>";
			else echo "<option value='Express'>Express Delivery ($5) - 2 hours</option>";
		}
		else {
			$_SESSION['delivery'] = "Normal";
			echo "<option value='Express'>Express Delivery ($5) - 2 hours</option>"; 
		}
		echo "</select></div></form>";
		
		echo "</td></tr>";



		$dobqry="SELECT * from shopper WHERE ShopperID=?";
		$dobstmt=$conn->prepare($dobqry);
		$dobstmt->bind_param("i",$_SESSION['ShopperID']);
		$dobstmt->execute();
		$dobresult=$dobstmt->get_result();
		$dobstmt->close();

		$disc = 0;
		if ($dobresult->num_rows>0){
			$row=$dobresult->fetch_array();
			$dob=$row["BirthDate"];
			if (date('m-d')==date("m-d", strtotime($dob))){
				$disc=$subTotalaDiscount*(5/100);
				echo "<tr><td class='tblContent'>Birthday Discount (5%): </td><td class='tblval'> -S$".number_format($disc,2)."</td></tr>";
			}
		}

		if ($_SESSION['delivery']=="Normal"){
			if ($subTotal-$disc>=50){
				$shippingCharge=0;
			}
			else{
				$shippingCharge=2;
			}
			
		}
		else{
			$shippingCharge=5;
		}
		
		echo "<tr><td class='tblContent'>Subtotal: </td><td class='tblval'>S$".number_format($subTotalaDiscount-$disc,2)."</td></tr>";
		echo "<tr><td class='tblContent'>Shipping Charge: </td><td class='tblval'>S$".number_format($shippingCharge,2)."</td></tr>";
		echo "<tr><td colspan='2' style='font-size:15px;'>*shipping charge will be waived for normal delivery if subtotal amount is $50 or above</td></tr>";
		echo "<tr><td colspan='2' style='padding-top:10px;'";
		echo "<form method='post' action='checkoutProcess.php' style='text-align:center;'>";
		echo "<input type='submit' class='checkout-btn' value='CHECK OUT'/>";
		echo "</form></td></tr>";
			
		echo "</table>";
		/*
		echo "<p style='text-align:right; font-size:20px'>
		Subtotal = S$" . number_format($subTotalaDiscount,2);
		$_SESSION["Total"]=round($subTotalaDiscount,2);	
		// To Do 7 (Practical 5):
		// Add PayPal Checkout button on the shopping cart page
		echo "<p style='text-align:right; font-size:20px'>
		Shipping Charge = S$" . number_format($shippingCharge,2);*/
		
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
