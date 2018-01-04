<?php
/**
 * Created by PhpStorm.
 * User: JarrodMaeckeler
 * Date: 2017-02-25
 * Time: 11:22 AM
 */

class Mail {

    public static function sendMail($to, $subject, $body) {
        return mail($to, $subject, $body);
    }

}