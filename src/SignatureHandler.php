<?php

namespace ecommpay;

/**
 * SignatureHandler
 *
 * @see https://developers.ecommpay.com/en/en_PP_Authentication.html
 */
class SignatureHandler
{
    const KEY_DELIMITER = ':'; // delimiter between key and value or in composite keys
    const ITEMS_DELIMITER = ';'; // delimiter between each parameter
    const ALGORITHM = 'sha512';
    const MAXIMUM_RECURSION_DEPTH = 3;

    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey;

    /**
     * Set of parameter`s keys that would be skipped while hashing.
     *
     * Example:
     * ```php
     * ['signature', 'user:private_data'];
     * ```
     *
     * @var string[]
     */
    private $ignoredKeys = [];

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
     * @see $ignoredKeys
     * @param string[] $ignoredKeys
     *
     * @return self
     */
    public function setIgnoredKeys(array $ignoredKeys): self
    {
        $this->ignoredKeys = $ignoredKeys;

        return $this;
    }

    /**
     * Check signature
     *
     * @param array $params
     * @param string $signature
     *
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
     *
     * @return string
     */
    public function sign(array $params): string
    {
        return $this->getSignature($this->getParamsStamp($params));
    }

    /**
     * @param string $paramsString
     *
     * @return string
     */
    private function getSignature(string $paramsString): string
    {
        $signHash = hash_hmac(self::ALGORITHM, $paramsString, $this->secretKey, true);

        return base64_encode($signHash);
    }

    private function getParamsStamp(array $params): string
    {
        $paramsToSign = $this->flattenParamsArray($params);

        ksort($paramsToSign, SORT_NATURAL);

        return implode(self::ITEMS_DELIMITER, $paramsToSign);
    }

    private function flattenParamsArray(array $params, string $prefix = '', int $depth = 0): array
    {
        $prefix = $prefix ? $prefix . self::KEY_DELIMITER : '';

        if ($depth >= self::MAXIMUM_RECURSION_DEPTH) {
            return [$prefix];
        }

        $flatten = [];
        foreach ($params as $key => $value) {
            $key = $prefix . strtolower($key);
            if (in_array($key, $this->ignoredKeys, true)) {
                continue;
            }

            if (is_array($value)) {
                $subArray = $this->flattenParamsArray($value, $key, $depth + 1);
                $flatten = array_merge($flatten, $subArray);
            } else {
                $value = $this->stringifyParamValue($value);
                $flatten[$key] = $key . self::KEY_DELIMITER . $value;
            }
        }

        return $flatten;
    }

    /**
     * @param $value
     *
     * @return string
     */
    private function stringifyParamValue($value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return (string)$value;
    }
}
