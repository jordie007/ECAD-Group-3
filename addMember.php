<?php
session_start();

//Reads user's input from Register Page
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

// Checks if inputted email already exists in database 
if ($dbresult->num_rows > 0) {
    $Message = "<h3 style='color:red'>Email already exists in database! Please try again with another email!</h3> <br />
    <h3><a href='register.php'>Try Again</a></h3>";
}

else  {
    $qry = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($qry);

    // "sssssssss" - 9 string parameters
    $stmt->bind_param("sssssssss", $name, $dateofbirth, $address, $country, $phone, $email, $password, $pwdqns, $pwdans);

    if ($stmt->execute()) { // SQL Statement executed successfully 
        
        // Assign a unique Shooper ID to new shopper
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry);
        
        while ($row = $result->fetch_array()) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        
        //Message to show that membership registration is successful
        $Message = "Registration successful!<br />
                    Your ShopperID is $_SESSION[ShopperID]<br />";
        //Save the Shopper Name in a session variable
        $_SESSION["ShopperName"] = $name;
    }
    else {
        $Message = "<h3 style='color:red'>Error in inserting record</h3>";
    }
    $stmt->close();
}
$conn->close();

include("header.php");
echo $Message;
include("footer.php");
?>