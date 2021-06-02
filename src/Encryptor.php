<?php

namespace ecommpay;

use Exception;

class Encryptor
{
    const CRYPTO_ALGO = 'aes-256-cbc';

    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @param string $message
     * @return string
     * @throws Exception
     */
    public function safeEncrypt(string $message): string
    {
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CRYPTO_ALGO));
        $encryptedMessage = openssl_encrypt($message, self::CRYPTO_ALGO, $this->key, 0, $iv);

        if ($encryptedMessage === false) {
            throw new Exception("Can't encrypt message: {$message}");
        }

        return base64_encode($encryptedMessage . '::' . $iv);
    }
}
