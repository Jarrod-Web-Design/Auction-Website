<?php
class User extends helper {
    protected static $table_name = "users";

    protected $id;
    protected $username;
    protected $password;
    protected $email;
    protected $verifystring;
    protected $active;
    public static $errorArray = array("pass"=>"Passwords do not match!", "taken"=>"Username taken, please use another.", "no"=>"Incorrect login details!", "failedlogin"=>"Incorrect login, please try again!");

    public static function authenticateUser($username, $password) {
        $db = database::getConnection();

        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1;";

        $bindVals = array(
            "username" => $username,
            "password" => $password
        );
        $userRecord = $db->fetchArray($sql, $bindVals);

        if($userRecord) {
            return array_shift(self::instantiateObjArr($userRecord));
        } else {
            return false;
        }
    }

    public static function generateRandomString() {
        $randomString = "";
        for ($i = 0; $i <16; $i ++) {
            $randomString .= chr(mt_rand(97, 122));
        }
        return $randomString;
    }

    public function mailUser() {
        $verifystring = urlencode($this->verifystring);
        $email = urlencode($this->email);

        $mail_body=<<<_MAIL_

Hi $this->username,

Please click on the following link to verify your new account:

verify.php?email=$email&verify=$verifystring

_MAIL_;

        mail::sendMail($this->email, $config_auctionname . " User verification", $mail_body);

    }
}
?>
