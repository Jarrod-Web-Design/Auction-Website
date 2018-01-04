<?php
require_once('includes/initialize.php');

// Check if PayPal response
if(isset($_POST["txn_id"]) || isset($_POST["txn_type"])) {

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value);// IPN fix
		$req .= "&$key=$value";
	}

	// assign posted variables to local variables
	$data = $_POST;

	$ch = curl_init($paypalURL);
	if($ch == false) {
		die("Connection failure");
	}
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSLVERSION, 6);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

	if(!($res = curl_exec($ch))) {
		curl_close($ch);
		die("Curl Error occurred: " . curl_error($ch));
	}
	curl_close($ch);

	if(strcasecmp($res, "VERIFIED") == 0) {

		// Validate payment (Check unique txnid & correct price)
		$valid_txnid = Payment::check_txnid($data['txn_id']);
		$valid_price = Payment::check_price($data['mc_gross'], $data['item_number']);

		// PAYMENT VALIDATED & VERIFIED!
		if($valid_txnid && $valid_price) {

			//Create Payment Object
			$paymentObj = Payment::createDB([
				'txn_id'          => $data['txn_id'],
				'mc_gross'        => $data['mc_gross'],
				'payment_status'  => $data['payment_status'],
				'item_number'     => $data['item_number'],
				'item_name'       => $data['item_name'],
				'payer_id'        => $data['payer_id'],
				'payer_email'     => $data['payer_email'],
				'first_name'      => $data['first_name'],
				'last_name'       => $data['last_name'],
				'address_street'  => $data['address_street'],
				'address_city'    => $data['address_city'],
				'address_state'   => $data['address_state'],
				'address_zip'     => $data['address_zip'],
				'address_country' => $data['address_country'],
				'payment_date'    => $data['payment_date'],
			]);

			if($paymentObj) {
				// Payment has been made & successfully inserted into the Database
				//mail('user@domain.com', 'PAYPAL POST - Payment has been made & successfully inserted into the Database', print_r($_POST, true));

			} else {
				// Error inserting into DB
				// E-mail admin or alert user
				// mail('user@domain.com', 'PAYPAL POST - INSERT INTO DB WENT WRONG', print_r($data, true));
			}
		} else {
			// Payment made but data has been changed
			// E-mail admin or alert user
		}

	} else if(strcmp($res, "INVALID") == 0) {

		// PAYMENT INVALID & INVESTIGATE MANUALLY!
		// E-mail admin or alert user

		// DEBUGGING
		//mail("user@domain.com", "PAYPAL DEBUGGING", "Invalid Response<br />data = <pre>" . print_r($_POST, true) . "</pre>");
	}
}
?>
