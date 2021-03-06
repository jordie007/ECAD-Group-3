<?php
session_start();

//Reads user's input from Update Profile Page
$name = $_POST["name"];
$dateofbirth = $_POST["birthdate"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$pwdqns = $_POST["pwdquestion"];
$pwdans = $_POST["pwdanswer"];

include_once("mysql_conn.php");

// Retrieve existing emails 
$dbemail = "SELECT * FROM Shopper WHERE Email = '$email'";
$dbresult = $conn->query($dbemail);

// Checks if inputted email already exists in database (besides the existing shopper's email)
if ($dbresult->num_rows > 1) {
    $Message = "<h3 style='color:red'>Email already exists in database! Please try again with another email!</h3>";
}

else {
    $shopperID = $_SESSION["ShopperID"];

    $qry = "UPDATE Shopper SET Name=?, BirthDate=?, Address=?, Country=?, Phone=?,
    Email=?, Password=?, PwdQuestion=?, PwdAnswer=? WHERE ShopperID=?";
    $stmt = $conn->prepare($qry);
    
    // "sssssssssi" - 10 string parameters
    $stmt->bind_param("sssssssssi", $name, $dateofbirth, $address, $country,
    $phone, $email, $password, $pwdqns, $pwdans, $shopperID);

    if ($stmt->execute()) { // SQL Statement executed successfully 
        
        // Retrieve and reassign ShopperID to existing Shopper
        $qry = "SELECT ShopperID FROM Shopper";
        $result = $conn->query($qry);
        
        while ($row = $result->fetch_array()) {
            $row["ShopperID"] = $_SESSION["ShopperID"];
        }
        
        //Message to show that membership registration is successful
        $Message = "Updating of profile successful! <br />";
    }
    else {
        $Message = "<h3 style='color:red'>Error in updating profile info</h3>";
    }
    $stmt->close();
}
$conn->close();

include("header.php");
echo $Message;
include("footer.php");
?>