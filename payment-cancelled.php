<?php

require_once("includes/initialize.php");
require("header.php");

echo <<<CANCELLED_
	<h1>Payment Cancelled</h1>
	<p>Your payment was cancelled.</p>
CANCELLED_;
require("footer.php");

?>
