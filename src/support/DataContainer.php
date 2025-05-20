<?php

namespace ecommpay\support;

use ecommpay\exception\ProcessException;
use ecommpay\exception\SdkException;

abstract class DataContainer
{

    /**
     * @var array
     */
    private $data;

    /**
     * @param string|array $data
     * @throws ProcessException
     */
    public function __construct($data)
    {
        $this->setData($data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @throws ProcessException
     */
    public function setData($data): DataContainer
    {
        $this->data = $this->toArray($data);
        return $this;
    }

    /**
     * Cast raw data to array
     *
     * @param $rawData
     *
     * @return array
     *
     * @throws ProcessException
     */
    private function toArray($rawData): array
    {
        if (is_string($rawData)) {
            $data = json_decode($rawData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ProcessException(
                    'Error on response decoding. Expected a valid JSON',
                    SdkException::DECODING_ERROR
                );
            }

            return $data;
        }

        if (is_array($rawData)) {
            return $rawData;
        }

        throw new ProcessException(
            'Error on response decoding. Unexpected type of data',
            SdkException::DECODING_ERROR
        );
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
        $data = $this->getData();
        foreach (explode('.', $namePath) as $key) {
            if (!isset($data[$key])) {
                return null;
            }
            $data = $data[$key];
        }
        return $data;
    }
}
