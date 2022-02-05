<?php 
// Detect the current session
session_start(); 
// Include the Page Layout header
include("header.php"); 
?>
<script type="text/javascript">
function validateForm()
{
    // To Do 1 - Check if password matched
	if (document.register.password.value != document.register.password2.value) {
        alert("Passwords not matched!");
        return false
    }
    return true;  // No error found
}
</script>

<div style="width:80%; margin:auto;">
<form name="register" action="addMember.php" method="post" 
      onsubmit="return validateForm()">
    
    <!-- Title Heading -->
    <div class="form-group row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Membership Registration</span>
        </div>
    </div>

    <!-- Name Field -->
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="name">Name:</label>
        <div class="col-sm-9">
            <input class="form-control" name="name" id="name" type="text" pattern="[a-zA-Z ]{3,}"
            title="Name should contain at least 3 letters" required /> <p style="color:red;">(required)
        </div>
    </div>

    <!-- DOB Field -->
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="birthdate">
            Date of Birth:</label>
        <div class="col-sm-9">
            <input class="form-control" name="birthdate" id="birthdate" 
                   type="date" required /> <p style="color:red;">(required)</p>
        </div>
    </div>

    <!-- Address Field -->
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="address">Address:</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="address" id="address"
                      cols="25" rows="4" required></textarea> <p style="color:red;">(required)
        </div>
    </div>

    <!-- Country Field -->
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="country">Country:</label>
        <div class="col-sm-9">
            <input class="form-control" name="country" id="country" type="text" pattern="[a-zA-Z ]{4,}"
            title="Country should contain at least 4 letters" required/> <p style="color:red;">(required)
        </div>
    </div>

    <!-- Phone Number Field -->
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="phone">Phone:</label>
        <div class="col-sm-9">
            <input class="form-control" name="phone" id="phone" type="text" pattern="[689][0-9]{7}"
             title="Phone Number should start with 6, 8 or 9 and have exactly 8 digits." required/> <p style="color:red;">(required)
        </div>
    </div>

    <!-- Email Field -->
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="email">
            Email Address:</label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" 
                   type="email" required /> <p style="color:red;">(required)
        </div>
    </div>

    <!-- Password Field -->
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="password">
            Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="password" 
            placeholder="Password should be at least 6 characters long." id="password" type="password"
            pattern=".{6,}" required /> <p style="color:red;">(required)
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="password2">
            Retype Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="password2"
            placeholder="Password should be at least 6 characters long." id="password2" type="password"
            pattern=".{6,}" required /> <p style="color:red;">(required)
        </div>
    </div>

    <!-- Security Field -->
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="pwdquestion">
            Select a Verification Question:</label>
        <div class="col-sm-9">
            <select class="form-control" name="pwdquestion" id="pwdquestion" required>
                <option value="" disabled selected>Select your option</option>
                <option value="Which secondary school?">Which secondary school did you study in?</option>
                <option value="What is your father's name?">What is your father's name?</option>
                <option value="What is your favourite food?">What is your favourite food?</option>
                <option value="What is your first concert?">What was the first concert you have attended in?</option>
            </select>
            <p style="color:red;">(required)
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="pwdanswer">
            Verification Answer:</label>
        <div class="col-sm-9">
            <input class="form-control" name="pwdanswer" id="pwdanswer" 
                   type="text" pattern="[a-zA-Z ]+" required /> <p style="color:red;">(required)</p>
        </div>
    </div>

    <!-- Register Button -->
    <div class="form-group row">       
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-secondary my-2" type="submit">Register</button>
        </div>
    </div>
</form>
</div>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>