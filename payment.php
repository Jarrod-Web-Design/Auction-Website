<?php
require_once('includes/intialize.php');

$validid = pf_validate_number($_GET['id'], "redirect", $config_basedir);

$bindVals = [
	"id" => $validid
];
$product = Item::findFirstCond($bindVals);
$product->getBids();

if(!$product) {
	echo "Error retrieving item details!";
	die();
}

$item_name = $product->getMemberVar('name');

$temp = $product->getMemberVar('bidObjs');
$itemWinnerBidObj = array_shift($temp);
$item_amount = $itemWinnerBidObj->getMemberVar("amount");

$itemOwnerObj = user::findFirstCond( array ( "id" => $product->getMemberVar("user_id")));


// Create query string for paypal
$querystring = '';

// ***Required Variables

// Append paypal account to querystring
$querystring .= "?business=" . urlencode($paypal_email) . "&";

// Append the product name and amount
$querystring .= "item_name=" . urlencode($item_name) . "&";
$querystring .= "amount=" . urlencode(91) . "&";

// Append paypal return addresses
$querystring .= "return=" . urlencode(stripslashes($return_url)) . "&";
$querystring .= "cancel_return=" . urlencode(stripslashes($cancel_url)) . "&";
$querystring .= "notify_url=" . urlencode(stripslashes($notify_url)) . "&";

// ***Optional Variables

// Set currency type
$querystring .= "currency_code=" . urlencode($currency) . "&";

// Return payment information back to user once returning from PayPal
$querystring .= "rm=" . urlencode(2) . "&";

$querystring .="cmd=" . urlencode(stripslashes("_xclick")) . "&";
$querystring .="item_number=" . urlencode(stripslashes($product->getMemberVar('id'))) . "&";

foreach($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$querystring .= "$key=$value&";
}

// Redirect to paypal IPN
header('Location:' . $paypalURL . $querystring);
exit();
?>
