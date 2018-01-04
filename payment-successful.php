<?php
/**
 * Created by PhpStorm.
 * User: JarrodMaeckeler
 * Date: 2017-03-15
 * Time: 11:23 AM
 */

require_once("includes/intialize.php");

if(isset($_POST['txn_id'])) {
    $item_name = $_POST['item_name'];
    $txn_id = $_POST['txn_id'];
    $payment_gross = $_POST['mc_gross'];
    $currency_code = $_POST['mc_currency'];
    $payment_status = $_POST['payment_status'];
    $paid_to = $_POST['business'];
    $shipper_fname = $_POST['first_name'];
    $shipper_lname = $_POST['last_name'];
    $addressStreet = $_POST['address_street'];
    $addressCity = $_POST['address_city'];
    $addressProvince = $_POST['address_state'];
    $addressPostal = $_POST['address_zip'];
    $addressCountry = $_POST['address_country'];
}

require("header.php");
if(!empty($txn_id)) {
    echo <<<RECEIPTPAYMENT_
<h1>Receipt of Payment</h1>
<table cellpadding='5'>
<tr>
    <td>Item Name: </td><td>$item_name</td>
</tr>
<tr>
    <td>Amount Paid: </td><td>$payment_gross $currency_code</td>
</tr>
<tr>
    <td>Shipping Address:</td><td>
    $shipper_fname $shipper_lname <br>
    $addressStreet <br>
    $addressCity $addressProvince $addressPostal <br>
    $addressCountry <br>
</td>
</tr>
<tr>
    <td>Paid To: </td><td>$paid_to</td>
</tr>
<tr>
    <td>Payment Status: </td><td>$payment_status</td>
</tr>
<tr>
    <td>Transaction ID: </td><td>$txn_id</td>
</tr>
<tr>
</tr>
</table>

<p>Your payment was successful.<br>Thank you for your business!</p>
RECEIPTPAYMENT_;

} else {
    echo "No information returned from PayPal";
}
require("footer.php");
?>