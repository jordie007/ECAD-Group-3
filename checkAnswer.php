<?php
session_start(); //Detect the current session

//Display Page Layout header with updated session state and links
include("header.php");

//Read the data input of previous page
$email = $_SESSION["Email"];
$securityans = $_POST["securityans"];

//Include the PHP file that establishes database connection handdle: $conn
include_once("mysql_conn.php");

$qry = "SELECT * FROM Shopper WHERE Email = '$email' AND PwdAnswer = '$securityans'";
$result = $conn->query($qry);

if ($result->num_rows > 0) {
    $new_pwd = "ecader";
    $update_pwd = "UPDATE Shopper SET Password=? WHERE Email=?";
    $stmt = $conn->prepare($update_pwd);

    // "ss" - 2 string parameters
    $stmt->bind_param("ss", $new_pwd, $email);
    $stmt->execute();
    $stmt->close();

    echo "<h5 style='color:green'>Your password has been changed to $new_pwd!</h5>";
    echo "<a href='login.php'>Proceed to Login Page</a>"; //Redirects user to login page
}

else {
    echo "<h5 style='color:red'>Wrong answer given!</h5>";
    echo "<a href='passwordQuestion.php'>Try Again</a>"; //Prompts user to answer the password question again
}

$conn->close();

//Display Page Layout Footer
include("footer.php");
?>