<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

// To Do 1: Check if user logged in 
if (! isset($_SESSION["ShopperID"])) {
    // redirect to login page if the session variable shopperid is not set
    header ("Location: login.php");
    exit;
}

else {
    include_once("mysql_conn.php");
    $qry = "SELECT * FROM Shopper WHERE ShopperID = ?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["ShopperID"]); // 'i' - integer
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            echo '
            <div style="width:80%; margin:auto;">
            
                <form name="register" action="updateShopper.php" method="post" onsubmit="return validateForm()">
                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <span class="page-title">Update Profile</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="name">Name:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="name" id="name" type="text" value= "'.$row['Name'].'" readonly />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="birthdate">Date of Birth:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="birthdate" id="birthdate" 
                                type="date" value= "'.$row['BirthDate'].'" readonly />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="address">Address:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="address" id="address"  cols="25" rows="4" type="text" >'.$row['Address'].'</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="country">Country:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="country" id="country"  value= "'.$row['Country'].'" type="text"
                            pattern="[a-zA-Z ]{4,}" title="Country should contain at least 4 letters" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="phone">Phone:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="phone" id="phone" value= "'.$row['Phone'].'" type="text"
                            pattern="[689][0-9]{7}" title="Phone Number should start with 6, 8 or 9 and have exactly 8 digits." />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="email">
                            Email Address:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="email" id="email" type="email" value="'.$row['Email'].'" readonly />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="pwd1">
                            Password:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pwd1"
                            placeholder="New password should be at least 6 characters long."
                            id="pwd1" type="password" pattern=".{6,}" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="pwd2">
                            Retype Password:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pwd2"
                            placeholder="New password should be at least 6 characters long."
                            id="pwd2" type="password" pattern=".{6,}" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="pwdquestion">
                            Select a Security Question:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="pwdquestion" id="pwdquestion" readonly>
                                <option value="'.$row['PwdQuestion'].'">'.$row['PwdQuestion'].'</option>
                            </select>    
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="pwdanswer">
                            Security Answer:</label>
                        <div class="col-sm-9">
                            <input class="form-control" name="pwdanswer" id="pwdanswer" value="'.$row['PwdAnswer'].'" type="text" readonly />
                        </div>
                    </div>

                    <div class="form-group row">       
                        <div class="col-sm-9 offset-sm-3">
                            <button class="btn btn-secondary my-2" id="loginBtn" type="submit">Update Profile</button>
                        </div>
                    </div>
                </form>

            </div>';
        }
    }
    else {
        echo "<h4 style='color:red;'>$_SESSION[ShopperID] does not exist!</h4>";
    }
}

?>

<script type="text/javascript">
function validateForm()
{
    // Check if password matched
	if (document.update.pwd1.value != document.update.pwd2.value) {
 	    alert("Given passwords are not the same!");
        return false;   // cancel submission
    }

    return true;
}
</script>

<?php 
// Include the Page Layout footer
include("footer.php");
?>