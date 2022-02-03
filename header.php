<?php
    // allow page to override the base
    $baseURI = $baseURI ?? ".";
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Donut Shop</title>
        <link rel="stylesheet" href="<?= $baseURI ?>/css/bootstrap.min.css">
        <script src="<?= $baseURI ?>/js/jquery-3.3.1.min.js"></script>

        <script src="<?= $baseURI ?>/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="<?= $baseURI ?>/css/site.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body style="background-color:#fffaed">
        <div class="container">
            <div class="row">
                <div class="col-sm-12" style="text-align:center">
                    <a href="<?= $baseURI ?>/index.php">
                        <img src="<?= $baseURI ?>/Images/yellow-donut.png" alt="Logo" class="img-fluid" style="width:50%;margin:0 auto;padding-top:3vh"/>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php include("navbar.php"); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" style="padding:15px;">

