<?php

namespace ecommpay\support;

use ecommpay\exception\ProcessException;
use ecommpay\exception\SdkException;

abstract class DataContainer
{
    private array $data;

    /**
     * @param string|array $data
     * @throws ProcessException
     */
    public function __construct($data)
    {
        $this->data = is_array($data) ? $data : $this->toArray($data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): DataContainer
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Cast raw data to array
     *
     * @param string $rawData
     *
     * @return array
     *
     * @throws ProcessException
     */
    public function toArray(string $rawData): array
    {
        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ProcessException('Error on response decoding', SdkException::DECODING_ERROR);
        }

        return $data;
    }

    /**
     * Get value by name path
     *
     * @param string $namePath
     *
     * @return mixed
     */
    public function getValue(string $namePath)
    {
        $keys = explode('.', $namePath);
        $callbackData = $this->getData();

        foreach ($keys as $key) {
            $value = $callbackData[$key] ?? null;

            if (is_null($value)) {
                return null;
            }

            $callbackData = $value;
        }

        return $callbackData;
    }
}
