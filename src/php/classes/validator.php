<?php
/**
 * @author Roel van Lierop <roel.van.lierop@gmail.com>
 */

/**
 * Validator class
 * 
 * This validator class checks sanitized input on validity with a RegEX
 */
class Validator {
    /**
     * check Method
     * 
     * Checks input data with a RegEX
     * 
     * @var array $fields A list of fields, data, and RegEX instructions to check
     * @return array Returns a array with the results from the validation
     */
    static function check( array $fields ): array 
    {
        $validationData = [
            'valid' => true,
            'errors' => []
        ];
        foreach($fields as $fieldName => $fieldObj) {
            if( !preg_match($fieldObj[1], htmlspecialchars( trim( $fieldObj[0] ) ) ) ) {
                $validationData['valid'] = false;
                $validationData['errors'][$fieldName] = $fieldName . ' is invalid';
            }
        }
        return $validationData;
    }
}