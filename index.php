<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
?>
<div style="margin:auto;text-align:center;
background-image: url('Images/welcome.jpg');
background-repeat: no-repeat;
background-position: center;min-height:50vh;">
<p style="font-size: 5vw;
    font-weight: 900;  position: absolute;
  top: 50%;
  transform: translateY(-50%);width:100%"> Welcome!<br/>Take a bite out of hunger</p>
</div>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>
