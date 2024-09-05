<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    header('Content-type: application/json');
    session_start();

    define( 'ABSPATH', dirname( __FILE__, 3 ) . '/' );

    include( '../classes/csrf.php' );
    include( '../classes/validator.php' );
    include( '../classes/user.php' );
    include( '../helpers/config.php' );

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $user = new User();
    echo $user->login( $data );