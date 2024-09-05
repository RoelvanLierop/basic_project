<?php

class Csrf {
    public function __construct(){
        if(
            isset( $_POST[ 'csrf' ] ) && isset( $_SESSION[ 'csrf' ] ) &&
            $_POST[ 'csrf' ] !== $_SESSION[ 'csrf' ]
        ) {
            die('Invalid CSRF token');
        }
    }
    public static function generate(){
        $datetime = new DateTime();
        $datetime->modify('+15 minutes');
        $_SESSION['csrf'] = hash_hmac('sha256', $datetime->format("H:i"), 'bundeling');
        echo $_SESSION['csrf'];
    }
}