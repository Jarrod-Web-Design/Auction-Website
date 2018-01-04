<?php
// ****** (A) ADD COMMANDS TO REQUIRE THE INITIALIZE FILE (BELOW) *******
require_once('includes/intialize.php');

// *** (B) USE THE CORRECT SESSION CLASS METHOD TO LOGOUT THE USER *****
$session->logoutUser($userObj);

// *** (C) REDIRECT THE USER TO THE INDEX1.PHP PAGE *****
header('Location: index.php');

?>
