<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a cenrally located container -->
<div style="width:80%; margin:auto;">
<form method="post">
	<div class="form-group row">
		<div class="col-sm-9 offset-sm-3">
			<span class="page-title">Forget Password</span>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="eMail">
         Email Address:</label>
		<div class="col-sm-9">
			<input class="form-control" name="eMail" id="eMail"
                   type="email" required />
		</div>
	</div>
	<div class="form-group row">      
		<div class="col-sm-9 offset-sm-3">
			<button type="submit">Submit</button>
		</div>
	</div>
</form>

<?php 
// Process after user click the submit button
if (isset($_POST["eMail"])) {
	
	// Read email address entered by user
	$eMail = $_POST["eMail"];
	
	// Retrieve shopper record based on e-mail address
	include_once("mysql_conn.php");

	$qry = "SELECT * FROM Shopper WHERE Email=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("s", $eMail); 	// "s" - string 
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) {
		// To Do 1: Update the default new password to shopper"s account
		$row = $result->fetch_array();
		$shopperId = $row["ShopperID"];
		$pwdQuestion = $row["PwdQuestion"];

		// Create new session cookie for shopper to answer password question
		$_SESSION["PwdQns"] = $pwdQuestion;
		$_SESSION["Email"] = $eMail;
		header("Location: passwordQuestion.php");
		exit;
	}

	else {
		echo "<p><span style='color:red;'>E-mail address does not exist!</span></p>";
	}

	$conn->close();
}
?>

</div> <!-- Closing container -->

<?php 
include("footer.php"); // Include the Page Layout footer
?>