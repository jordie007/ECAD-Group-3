<?php 
session_start();
if (isset($_POST['action'])) {
 	switch ($_POST['action']) {
    	case 'add':
        	addItem();
            break;
        case 'update':
            updateItem();
            break;
		case 'shipping':
			updateShipping();
			break;
		case 'remove':
            removeItem();
            break;
    }
}


function addItem() {
	// Check if user logged in 
	if (! isset($_SESSION["ShopperID"])) {
		// redirect to login page if the session variable shopperid is not set
		header ("Location: login.php");
		exit;
	}
	// TO DO 1
	// Write code to implement: if a user clicks on "Add to Cart" button, insert/update the 
	// database and also the session variable for counting number of items in shopping cart.
	include_once("mysql_conn.php"); // Establish database connection handle: $conn
	// Check if a shopping cart exist, if not create a new shopping cart
	
	//check if databse got enough quanityt, prompt error msg when not enuf qty
	//if ()



	$qtyAdded=0;
	if (! isset($_SESSION["Cart"])){
		$qry="INSERT INTO ShopCart(ShopperID) VALUES(?)";
		$stmt=$conn->prepare($qry);
		$stmt->bind_param("i",$_SESSION["ShopperID"]);
		$stmt->execute();
		$stmt->close();
		$qry="SELECT LAST_INSERT_ID() AS ShopCartID";
		$result=$conn->query($qry);
		$row=$result->fetch_array();
		$_SESSION["Cart"]=$row["ShopCartID"];
	}
  	// If the ProductID exists in the shopping cart, 
  	// update the quantity, else add the item to the Shopping Cart.
  	$pid=$_POST["product_id"];
	$quantity=$_POST["quantity"];
	$qry="SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
	$stmt=$conn->prepare($qry);
	$stmt->bind_param("ii",$_SESSION["Cart"],$pid);
	$stmt->execute();
	$result=$stmt->get_result();
	$stmt->close();
	$addNewItem=0;
	if ($result->num_rows>0){
		$rows=$result->fetch_array();
		$cartQty=$rows["Quantity"];
		$qry="UPDATE ShopCartItem SET Quantity=LEAST(Quantity+?,30)
		WHERE ShopCartID=? AND ProductID=?";
		$stmt=$conn->prepare($qry);
		$stmt->bind_param("iii",$quantity,$_SESSION["Cart"],$pid);
		$stmt->execute();
		$stmt->close();
		
		if ($cartQty+$quantity>30){
			$qtyAdded=30-$cartQty;
		}
		else{
			$qtyAdded=$quantity;
		}
	}
	else{
		if ($quantity>30){
			$quantity=30;
			$qtyAdded=30;
		}
		else{
			$qtyAdded=$quantity;
		}
		$qry="INSERT INTO ShopCartItem(ShopCartID,ProductID,Price,Name,Quantity)
		SELECT ?,?,Price,ProductTitle,? FROM Product WHERE ProductID=?";
		$stmt=$conn->prepare($qry);
		$stmt->bind_param("iiii",$_SESSION["Cart"],$pid,$quantity,$pid);
		$stmt->execute();
		$stmt->close();
	}


  	$conn->close();
  	// Update session variable used for counting number of items in the shopping cart.
	if (isset($_SESSION["NumCartItem"])){
		$_SESSION["NumCartItem"]=$_SESSION["NumCartItem"]+$qtyAdded;
	}
	else{
		$_SESSION["NumCartItem"]=$qtyAdded;
	}
	// Redirect shopper to shopping cart page
	header("Location: shoppingCart.php");
	exit;
}

function updateItem() {
	// Check if shopping cart exists 
	if (! isset($_SESSION["Cart"])) {
		// redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
	$cartQty=0;
	$cartid=$_SESSION["Cart"];
	$pid=$_POST["product_id"];
	$quantity=$_POST["quantity"];
	if ($quantity<=0){
		removeItem();
		exit;
	}
	
	
	include_once("mysql_conn.php");
	$qqry="SELECT Quantity FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
	$qstmt=$conn->prepare($qqry);
	$qstmt->bind_param("ii",$cartid,$pid);
	$qstmt->execute();
	$qresult=$qstmt->get_result();
	$qstmt->close();
	$addNewItem=0;
	if ($qresult->num_rows>0){
		$qrows=$qresult->fetch_array();
		$cartQty=$qrows["Quantity"];
	}
	
	// TO DO 2
	// Write code to implement: if a user clicks on "Update" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	
	

	if ($quantity>30){
		$_SESSION["Error"]="Unable to add more than 30 item of each product, please checkout and add the remaining quantity in another cart";
		header("Location: shoppingCart.php");
		exit;
	}
	
	$qry="UPDATE ShopCartItem SET Quantity=? WHERE ProductID=? AND ShopCartID=?";
	$stmt=$conn->prepare($qry);
	$stmt->bind_param("iii",$quantity,$pid,$cartid);
	$stmt->execute();
	$stmt->close();
	$conn->close();
	header("Location: shoppingCart.php");
	$_SESSION["NumCartItem"]=($_SESSION["NumCartItem"]-$cartQty)+$quantity;
	exit;
}
function updateShipping() {
	// Check if shopping cart exists 
	if (! isset($_SESSION["Cart"])) {
		// redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
	$_SESSION['delivery']=$_POST['delivery'];
	header("Location: shoppingCart.php");
	exit;
}
function removeItem() {
	if (! isset($_SESSION["Cart"])) {
		// redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
	// TO DO 3
	// Write code to implement: if a user clicks on "Remove" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	$cartid=$_SESSION["Cart"];
	$pid=$_POST["product_id"];
	$quantity=$_POST["prod_quantity"];
	include_once("mysql_conn.php");
	$delqry="DELETE FROM ShopCartItem WHERE ProductID=? AND ShopCartID=?";
	$delstmt=$conn->prepare($delqry);
	$delstmt->bind_param("ii",$pid,$cartid);
	$delstmt->execute();
	$delstmt->close();
	$conn->close();
	$_SESSION["NumCartItem"]=$_SESSION["NumCartItem"]-$quantity;
	header("Location: shoppingCart.php");
	exit;
}		
?>
