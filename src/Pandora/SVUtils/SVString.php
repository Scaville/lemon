<?php

namespace Scaville\Lemon\Pandora\SVUtils;

class SVString {

    /**
     * Returns a boolean if the value informated is empty or null.
     * @param string $value
     * @param boolean $trim
     * @return boolean
     */
    public static function isEmptyOrNull($value, $trim = true) {
        if ($trim) {
            $value = trim($value);
        }
        if (null === $value || '' === $value) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns null if the variable is null or empty.
     * @param variant $value
     * @param boolean $trim
     * @return variant
     */
    public static function nullIfEmpty($value, $trim = true) {
        if ($trim) {
            $value = trim($value);
        }
        try {
            if (null === $value || '' === trim($value)) {
                return null;
            } else {
                return $value;
            }
        } catch (Exception $ex) {
            return null;
        }
    }

}
