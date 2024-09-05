<?php
/**
 * @author Roel van Lierop <roel.van.lierop@gmail.com>
 */

/**
 * Global config method
 * 
 * Retrieves a configuration value based on the input string
 * 
 * @var string String with dot-separated routing inside our configuration file. Using dots searches levels of the configuration
 * @return mixed Returns the configuration value as a string or crashes the page
 */
function config( string $keystring ): mixed
{
    // Match the string for validity
    if( preg_match("/[A-Za-z\.]+/", $keystring ) ) {
        // Explode the string and search all levels of the configuration for our value
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
        // if we have a config value, return that
        if( $rd !== null ) {
            return strval( $rd );
        }
    }
    // crash the page if something is wrong
    die('Invalid config key provided');
}