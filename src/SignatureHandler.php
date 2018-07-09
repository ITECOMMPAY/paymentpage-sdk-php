<?php

namespace ecommpay;

/**
 * SignatureHandler
 *
 * @see https://developers.ecommpay.com/en/en_PP_Authentication.html
 */
class SignatureHandler
{
    const ITEMS_DELIMITER = ';';
    const ALGORITHM = 'sha512';
    const MAXIMUM_RECURSION_DEPTH = 3;

    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey;

    /**
     * __construct
     *
     * @param string $secretKey
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Check signature
     *
     * @param array $params
     * @param string $signature
     * @return boolean
     */
    public function check(array $params, $signature): bool
    {
        return $this->sign($params) === $signature;
    }

    /**
     * Return signature
     *
     * @param array $params
     * @return string
     */
    public function sign(array $params): string
    {
        $stringToSign = implode(self::ITEMS_DELIMITER, $this->getParamsToSign($params));
        return base64_encode(hash_hmac(self::ALGORITHM, $stringToSign, $this->secretKey, true));
    }

    /**
     * Get parameters to sign
     *
     * @param array $params
     * @param array $ignoreParamKeys
     * @param int $currentLevel
     * @param string $prefix
     *
     * @return array
     */
    private function getParamsToSign(array $params, array $ignoreParamKeys = [], $currentLevel = 1, $prefix = '')
    {
        $paramsToSign = [];

        foreach ($params as $key => $value) {
            $paramKey = ($prefix ? $prefix . ':' : '') . $key;
            if (is_array($value)) {
                if ($currentLevel >= self::MAXIMUM_RECURSION_DEPTH) {
                    $paramsToSign[$paramKey] = $paramKey . ':';
                } else {
                    $subArray = $this->getParamsToSign($value, $ignoreParamKeys, $currentLevel + 1, $paramKey);
                    $paramsToSign = array_merge($paramsToSign, $subArray);
                }
            } else {
                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                } else {
                    $value = (string)$value;
                }

                $paramsToSign[$paramKey] = $paramKey . ':' . $value;
            }
        }

        if ($currentLevel === 1) {
            ksort($paramsToSign, SORT_NATURAL);
        }

        return $paramsToSign;
    }
}
