<?php
	define('DB_HOST', 'localhost');
	define('DB_USER', 'jmaeckeler');
	define('DB_PASSWORD', 'tmfqtmfq');
	define('DB_NAME', 'jmaeckelerauction');

	$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin-webscr';
	$paypal_email = 'jarrodmaeckeler-facilitator@gmail.com';
	$currency = 'CAD';
	$return_url = 'https://php.scweb.ca/~jmaeckeler/auction/payment-successful.php';
    $cancel_url = 'https://php.scweb.ca/~jmaeckeler/auction/payment-cancelled.php';
    $notify_url = 'https://php.scweb.ca/~jmaeckeler/auction/confirm.php';
?>