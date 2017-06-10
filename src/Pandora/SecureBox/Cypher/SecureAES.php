<?php

namespace Scaville\Lemon\Pandora\SecureBox\Cypher;

class SecureAES{

    /**
     * Decrypt a string on AES 256.
     * @param string $value
     * @param string $key - 32 Bytes length (256 bits);
     * @return string
     */
    public static function decrypt($value, $key = null) {
        return rtrim(
                mcrypt_decrypt(
                        MCRYPT_RIJNDAEL_256, $key, base64_decode($value), MCRYPT_MODE_ECB, mcrypt_create_iv(
                                mcrypt_get_iv_size(
                                        MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB
                                ), MCRYPT_RAND
                        )
                ), "\0"
        );
    }

    /**
     * Crypt a string string on AES 256.
     * @param string $value
     * @param string $key - 32 Bytes length (256 bits);
     * @return base64
     */
    public static function encrypt($value, $key = null) {
        return rtrim(
                base64_encode(
                        mcrypt_encrypt(
                                MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, mcrypt_create_iv(
                                        mcrypt_get_iv_size(
                                                MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB
                                        ), MCRYPT_RAND)
                        )
                ), "\0"
        );
    }

}