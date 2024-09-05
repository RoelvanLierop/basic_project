<?php

function config( String $keystring ) {
    if( preg_match("/[A-Za-z\.]+/", $keystring ) ) {
        $keyParts = explode('.', $keystring);
        if( count($keyParts) > 0 ) {
            $data = json_decode( file_get_contents( ABSPATH . '/config.json'), true );
            $rd = $data[$keyParts[0]];
            array_shift($keyParts);
            if( count($keyParts) > 0 ) {
                foreach($keyParts as $i => $key) {
                    $rd = $rd[$key];
                }
            }
        }
        if( $rd !== null ) {
            return $rd;
        }
    }
    die('Invalid config key provided');
}