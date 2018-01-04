<?php
class Session {
    private $loggedIn = false;
    private $userObj = false;

    public function __construct() {
        session_start();
        $this->refreshLogin();
    }

    private function refreshLogin() {
        if (isset($_SESSION['USEROBJ'])) {
            $this->loginUser($_SESSION['USEROBJ']);
            $this->loggedIn = true;
        }
    }

    public function getLoggedInStatus() {
        return $this->loggedIn;
    }

    public function getUserObj() {
        return $this->userObj;
    }

    public function loginUser($userObj) {
        $this->loggedIn = true;
        $this->userObj = $userObj;
        $_SESSION['USEROBJ'] = $userObj;
    }

    public function logoutUser() {
        $this->loggedIn = false;
        $this->userObj = false;
        unset($_SESSION);
        session_destroy();
    }

}

$session = new Session();

?>
