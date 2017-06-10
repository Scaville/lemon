<?php

namespace Scaville\Lemon\Pandora\SecureBox\Cypher;

use Scaville\Lemon\Constants\Messages;

class SecureHASH{

    private static $key;

    /**
     * Defines the global key to calc HMAC.
     * @param string $secretKey
     */
    public static function setSecretKey($secretKey) {
        self::$key = $secretKey;
    }

    /**
     * Crypt a string on MD5 with HMAC
     * @param string $value
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public static function MD5_HMAC($value, $key = null) {
        $key = (null !== $key) ? $key : self::$key;

        if (null === $key) {
            throw new \Exception(Messages::SECRET_KEY_NOT_DEFINED);
        }

        return hash_hmac('md5', $value, $key);
    }

    /**
     * Crypt a string on SHA-256 with HMAC
     * @param string $value
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public static function SHA_256_HMAC($value, $key = null) {
        $key = (null !== $key) ? $key : self::$key;

        if (null === $key) {
            throw new \Exception(Messages::SECRET_KEY_NOT_DEFINED);
        }

        return hash_hmac('sha256', $value, $key);
    }

}
