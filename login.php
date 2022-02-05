<?php
session_start();
include("header.php");

$formAction = "checkLogin.php";
if (isset($_REQUEST["redirect"])) {
    $formAction .= "?redirect=" . $_REQUEST["redirect"];
}
?>

<div class="container">
    <div class="col-sm-8 mx-auto">
        <form action="<?= $formAction ?>" method="POST">
            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <span class="page-title">Member Login</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="email">Email Address:</label>
                <div class="col-sm-9">
                    <input class="form-control" type="email" name="email" id="email" required />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="password">Password:</label>
                <div class="col-sm-9">
                    <input class="form-control" type="password" name="password" id="password" required />
                </div>
            </div>
            <div class="form-group row">
                <?php if ($_GET["error"] ?? false) { ?>
                    <div class="col-sm-9 offset-sm-3 text-danger pb-2">
                        Invalid login credentials!
                    </div>
                <?php } ?>
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <div class="pt-3">
                        <a href="register.php">Sign up</a> |
                        <a href="forgetPassword.php">Forget Password</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include("footer.php"); ?>
