<?php

namespace Scaville\Lemon\Pandora\SecureBox\Cypher;

use phpseclib\Crypt\RSA;
use Scaville\Lemon\Constants\Messages;


class SecureRSA {

    private $publicKey;
    private $privateKey;

    /**
     * Load the public key.
     * @param base64 $key
     */
    public function loadPublicKey($key) {
        $this->publicKey = base64_decode($key);
    }

    /**
     * Load the private key.
     * @param string $key
     */
    public function loadPrivateKey($key) {
        $this->privateKey = base64_decode($key);
    }

    /**
     * Generate the key pair public/private RSA.
     * @return array
     */
    public function generateKeys() {
        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        extract($rsa->createKey(4096));

        return array(
            "publicKey" => base64_encode($publickey),
            "privateKey" => base64_encode($privatekey)
        );
    }

    /**
     * Decrypt using the private key.
     * @param base64 $value
     * @param base64 $key
     * @return string
     * @throws \Exception
     */
    public function decrypt($value, $key = null) {
        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $key = (null !== $this->privateKey) ? $this->privateKey : base64_decode($key);
        
        if(null === $key){
            throw new \Exception(Messages::PRIVATE_KEY_NOT_DEFINED);
        }
        
        $rsa->loadKey($key);
        
        return $rsa->decrypt(base64_decode($value));
    }

    /**
     * Crypt using the private key.
     * @param string $value
     * @param base64 $key
     * @return base64
     * @throws \Exception
     */
    public function encrypt($value, $key = null) {
        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        
        $key = (null !== $this->publicKey) ? $this->publicKey : base64_decode($key);
        
        if(null === $key){
            throw new Exception(Messages::PUBLIC_KEY_NOT_DEFINED_KEY_NOT_DEFINED);
        }
        
        $rsa->loadKey($key);
        
        return base64_encode($rsa->encrypt($value));
    }

}
