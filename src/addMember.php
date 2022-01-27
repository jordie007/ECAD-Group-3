<?php
session_start();

$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = strtolower($_POST["email"]);
$password = password_hash($_POST["password"],PASSWORD_DEFAULT);

include_once("mysql_conn.php");

$qry = "INSERT INTO Shopper (Name,Address,Country,Phone,Email,Password)
        VALUES (?,?,?,?,?,?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("ssssss", $name,$address,$country,$phone,$email,$password);

if ($stmt->execute()) {
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry);
    while ($row = $result->fetch_array()) {
        $_SESSION["ShopperID"] = $row["ShopperID"];
    }

    $Message = "Registration successful!<br />
                Your ShopperID is $_SESSION[ShopperID]<br />";
    $_SESSION["ShopperName"] = $name;
}
else {
    $Message = "<h3 style='color:red'>Error in inserting record</h3>";
}

$stmt->close();
$conn->close();

include("header.php");
echo $Message;
include("footer.php");
?>