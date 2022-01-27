<?php
session_start();
unset($_SESSION["ShopperName"]);
unset($_SESSION["ShopperID"]);
unset($_SESSION["Cart"]);
unset($_SESSION["NumCartItem"]);
session_destroy();
header("Location: index.php");
exit;
?>