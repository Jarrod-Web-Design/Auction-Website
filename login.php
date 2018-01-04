<?php
// ****** (A) ADD COMMANDS TO REQUIRE THE INITIALIZE FILE (BELOW) *******
require_once('includes/intialize.php');

if($_POST['submit']) {
    
    //Attempt to authenticate the user given the username and password. Returns the user object if successful or false if unsuccessful
    $userObj = user::authenticateUser($_POST['username'], $_POST['password']);
    
	if($userObj) {	
        //If the user has been verified
		if($userObj->getMemberVar('active')) {
            
			// *** (B) REGISTER THE USEROBJ TO THE SESSION OBJECT USING THE CORRECT METHOD BELOW *****
            //Register the user as being logged in

			$session->loginUser($userObj);
			

    		switch($_GET['ref']) {
				case "addbid":
					header("Location: itemdetails.php?id=" . $_GET['id'] . "#bidbox");
				break;

				case "newitem":
					header("Location: newitem.php");
				break;

				case "images":
					header("Location: addimages.php?id=" . $_GET['id']);
				break;
					
				default:
					header("Location: index.php");
				break;
			}
		}
		else {
			require("header.php");
			echo "This account is not verified yet. You were emailed a link to verify the account. Please click on the link in the email to continue.";
		}			
	}
	else {
		header("Location: login.php?error=failedlogin");
	}
}
else {

	require("header.php");

	echo "<h1>Login</h1>";

	
	if(isset($_GET['error'])) {
        // ***** (C) RETRIEVE THE ERROR MESSAGE USING THE CORRET METHOD IN THE USER CLASS **** 
		
		$errorMsg = user::displayError($_GET['error']);
        
		if ($errorMsg != false) {
            echo $errorMsg;
        }
	}

?>
<form action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="post">

	<label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required><br><br>

    <label><b>Password </b></label>
    <input type="password" placeholder="Enter Password" name="password" required><br><br>

    <input type="submit" name="submit" value="Login">
</form>
Don't have an account? Go and <a href="register.php">Register</a>!
<?php
}

require("footer.php");
?>