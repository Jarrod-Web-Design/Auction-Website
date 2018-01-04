<?php
require_once("includes/intialize.php");

if($_POST['submit']) {
	if($_POST['password1'] == $_POST['password2']) {
        
		// ***************************** (A) code goes here! *****************************
		// ** Create new user object using form data. Verify user doesn't already exist **

        $username = $_POST['username'];
        $password = $_POST['password1'];
        $email = $_POST['email'];

        $newUser = User::createDB(
                [
            "id" => 0,
            "username" => $username,
            "password" => $password,
            "email" => $email,
            "verifystring" => User::generateRandomString(),
            "active" => 0
                ],
            [
                "email" => $email
            ]


        );

        //If boolean false is returned then the username has been taken
		if(!$newUser) {
			header("Location: register.php?error=taken");	
		} else {
		
		// ***************** (B) code goes here! *******************
		// ** Send an email to the new user's given email address **

            $newUser->mailUser();


		
   			require("header.php");
			echo "A link has been emailed to the address you entered below. Please follow the link in the email to validate your account."; 			
		}
	}
	else {
        //Passwords do not match
		header("Location: register.php?error=pass");
	}

} else {
	require("header.php");
	if (isset($_GET['error'])) {
        $errorMsg = user::displayError($_GET['error']);
        if ($errorMsg != false) {
            echo $errorMsg;
        }
    }
?>
	<h2>Register</h2>
	To register on the <?php echo $config_auctionname; ?> , fill in the form below.
	<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
	<table>
	<tr>
		<td>Username</td>
		<td><input type="text" name="username"></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" name="password1"></td>
	</tr>
	<tr>
		<td>Password (again)</td>
		<td><input type="password" name="password2"></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><input type="text" name="email"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="Register!"></td>
	</tr>
	</table>
	</form>

<?php
}

require("footer.php");

?>