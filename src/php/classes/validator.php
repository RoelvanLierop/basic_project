<?php

class Validator {
    static function check( Array $fields ):array {
        $validationData = [
            'valid' => true,
            'errors' => []
        ];
        foreach($fields as $fieldName => $fieldObj) {
            if( !preg_match($fieldObj[1], htmlspecialchars(trim($fieldObj[0])) ) ) {
                $validationData['valid'] = false;
                $validationData['errors'][$fieldName] = $fieldName . ' is invalid';
            }
        }
        return $validationData;
    }
}