<?php
session_start();

//Reads user's input from Register Page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = password_hash($_POST["password"],PASSWORD_DEFAULT);

include_once("mysql_conn.php");

// Retrieve existing emails 
$dbemail = "SELECT * FROM Shopper WHERE Email = '$email'";
$dbresult = $conn->query($dbemail);

// Checks if inputted email already exists in database 
if ($dbresult->num_rows > 0) {
    $Message = "<h3 style='color:red'>Email already exists in database! Please try again with another email!</h3>";
}

else  {
    $qry = "INSERT INTO Shopper (Name, Address, Country, Phone, Email, Password)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($qry);

    // "ssssss" - 6 string parameters
    $stmt->bind_param("ssssss", $name, $address, $country, $phone, $email, $password);

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