<?php
session_start();
include("header.php");
?>
<div style="width:80%;margin:auto">
    <form action="checkLogin.php" method="POST">
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Member Login</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">Email Address:</label>
            <div class="col-sm-9">
                <input class="form-control" type="email" name="email" id="email" required/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="password">Password:</label>
            <div class="col-sm-9">
                <input class="form-control" type="password" name="password" id="password" required/>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn btn-primary">Login</button>
                <p>Please sign up if you do not have an account.</p>
                <p><a href="forgetPassword.php">Forget Password</a></p>
            </div>
        </div>
    </form>
</div>
<?php include("footer.php"); ?>