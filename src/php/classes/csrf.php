<?php
/**
 * @author Roel van Lierop <roel.van.lierop@gmail.com>
 */

/**
 * Csrf Class
 * 
 * Basic class for generating and verifying a CSRF token
 */
class Csrf {

    /**
     * Constructor
     * 
     * The constructor always checks the CSRF for validity
     */
    public function __construct(){
        if(
            isset( $_POST[ 'csrf' ] ) && isset( $_SESSION[ 'csrf' ] ) &&
            htmlspecialchars( trim( $_POST[ 'csrf' ] ) ) !== $_SESSION[ 'csrf' ]
        ) {
            die('Invalid CSRF token');
        }
    }

    /**
     * Static generate Method
     * 
     * CSRF token generation method, this will create a CSRF token to store in a hidden form field
     */
    public static function generate(): void
    {
        $datetime = new DateTime();
        $datetime->modify('+15 minutes');
        $_SESSION['csrf'] = htmlspecialchars( trim( hash_hmac('sha256', $datetime->format("H:i"), 'basicproject') ) );
        echo $_SESSION['csrf'];
    }
}