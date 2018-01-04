<?php
/**
 * Created by PhpStorm.
 * User: JarrodMaeckeler
 * Date: 2017-03-15
 * Time: 10:59 AM
 */

class Payment extends helper {

    protected static $table_name = "payments";
    protected $id;
    protected $txn_id;
    protected $mc_gross;
    protected $payment_status;
    protected $item_number;
    protected $item_name;
    protected $payer_id;
    protected $payer_email;
    protected $first_name;
    protected $last_name;
    protected $addrss_street;
    protected $address_city;
    protected $address_state;
    protected $address_zip;
    protected $address_country;
    protected $payment_date;

    public static function generatePayment($id) {
        $PayPalButton = <<<HEREDOC_
<a href="https://php.scweb.ca/~jmaeckeler/auction/payment.php?id=$id">
<img src="https://www.paypalobjects.com/en_US/i/btn//btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online" border="0">
</a>
HEREDOC_;

        return $PayPalButton;

    }

    public static function check_txnid($tnxid) {
        $db = Database::getConnection();
        $result = $db->rowCount("SELECT * FROM 'payments' WHERE txn_id = '" . tnxid . "'");

        if ($result == 0) {
            return true;
        }

        return false;
    }

    public static function check_price($amount, $id) {
        $db = Database::getConnection();
    $result = $db->fetchArray("SELECT MAX(amount) as price FROM 'bids' WHERE item_id = '" . $id . "' LIMIT 1;");

    if ($result) {
        $result = array_shift($result);
        if(count($result) == 1) {
            $amt = (float)$result['price'];
            if($amount == $amt){
                return true;
            }
        }
    }

    return false;
    }

}