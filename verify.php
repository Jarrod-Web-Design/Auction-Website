<?php
require_once("includes/intialize.php");

require("header.php");

$verifystring = addslashes(urldecode($_GET['verify']));
$verifyemail = addslashes(urldecode($_GET['email']));

// ***************************** (A) code goes here! *******************************
// ** Check to see if the user given email and verify string matches the database **

$userObj = user::FindFirstCond(array (
    "email" => "$verifyemail",
    "verifystring" => "$verifystring"
));


//If there was a match in the database, set the active property to '1' and update the database
if($userObj) {        

	// ***************************** (B) code goes here! *******************************
	// ** Set the 'active' property of the user to 1 & update the database record **

    $userObj->setMemberVar('active', 1);
    $result = $userObj->updateDB();
	
	
	if ($result){
	   echo "Your account has now been verified. You can now <a href='login.php'>log in</a>";
    } else {
        echo "Update failed!";
    }
}
else {
    echo "This account could not be verified.";
}

echo " Verification value:" . $verifystring;

require("footer.php");

?>

