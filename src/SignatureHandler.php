<?php

namespace ecommpay;

use function in_array;

/**
 * SignatureHandler
 *
 * @see https://developers.ecommpay.com/en/en_PP_Authentication.html
 */
class SignatureHandler
{
    const ITEMS_DELIMITER = ';';
    const ALGORITHM = 'sha512';
    const IGNORED_KEYS = ['frame_mode'];

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
    public function __construct(string $secretKey)
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
    public function check(array $params, string $signature): bool
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
        $stringToSign = implode(self::ITEMS_DELIMITER, $this->getParamsToSign($params, self::IGNORED_KEYS));
        return base64_encode(hash_hmac(self::ALGORITHM, $stringToSign, $this->secretKey, true));
    }

    /**
     * Get parameters to sign
     *
     * @param array $params
     * @param array $ignoreParamKeys
     * @param string $prefix
     * @param bool $sort
     * @return array
     */
    private function getParamsToSign(
        array $params,
        array $ignoreParamKeys = [],
        string $prefix = '',
        bool $sort = true
    ): array {
        $paramsToSign = [];

        foreach ($params as $key => $value) {
            if (in_array($key, $ignoreParamKeys, true)) {
                continue;
            }

            $paramKey = ($prefix ? $prefix . ':' : '') . str_replace(':', '::', $key);
            if (is_array($value)) {
                $subArray = $this->getParamsToSign($value, $ignoreParamKeys, $paramKey, false);
                $paramsToSign = array_merge($paramsToSign, $subArray);
            } else {
                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                } else {
                    $value = (string)$value;
                }

                $paramsToSign[$paramKey] = $paramKey . ':' . $value;
            }
        }

        if ($sort) {
            ksort($paramsToSign, SORT_NATURAL);
        }

        return $paramsToSign;
    }
}
