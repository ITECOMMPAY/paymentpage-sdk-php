<?php

namespace Gate;

/**
 * Callback 
 * 
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com> 
 */
class Callback
{
	/**
	 * Callback data as array
	 * 
	 * @var array
	 */
	private $cbData;
	
	/**
	 * Signature Handler
	 * 
	 * @var SignatureHandler
	 */
	private $signatureHandler;
	
	/**
	 * __construct 
	 * 
	 * @param string $rawData RAW data from gate
	 * @param SignatureHandler $signatureHandler 
	 * @throws ProcessException
	 */
	public function __construct($rawData, $signatureHandler)
	{
		$this->cbData = $this->toArray($rawData);
		$this->signatureHandler = $signatureHandler;
		if (!$this->checkSignature()) {
			throw new ProcessException("Signature {$this->getSignature()} is invalid");
		}
	}

	/**
	 * Get payment info
	 * 
	 * @return mixed
	 */
	public function getPayment()
	{
		return $this->getValueByName('payment');
	}

	/**
	 * Get payment status 
	 * 
	 * @return string
	 */
	public function getPaymentStatus()
	{
		return $this->getValueByName('status', $this->getPayment());
	}

	/**
	 * Get payment ID 
	 * 
	 * @return string
	 */
	public function getPaymentId()
	{
		return $this->getValueByName('id', $this->getPayment());
	}

	/**
	 * Get signature 
	 * 
	 * @return string
	 */
	public function getSignature()
	{
		return $this->getValueByName('signature');
	}

	/**
	 * Cast raw data to array 
	 * 
	 * @param string $rawData 
	 * @return array
	 */
	public function toArray($rawData)
	{
		return json_decode($rawData, true);
	}

	/**
	 * Get value by name 
	 * 
	 * @param string $name Param name
	 * @param array $stuff tmp stuff
	 * @return mixed
	 */
	private function getValueByName($name, array $stuff = array())
	{
		if (!$stuff) {
			$stuff = $this->cbData;
		}

		if (!array_key_exists($name, $stuff)) {
			foreach ($stuff as $key => $value) {
				if (is_array($value)) {
					return $this->getValueByName($name, $value);
				}
			}
		} else {
			return $stuff[$name];
		}
	}
	
	/**
	 * checkSignature 
	 * 
	 * @return boolean
	 */
	public function checkSignature()
	{
		$data = $this->cbData;
		$signature = $this->getSignature();
		$this->killParam('signature', $data);
		return $this->signatureHandler->check($data['body'], $signature);
	}

	/**
	 * Unset param at callback adata 
	 * 
	 * @param string $name param name
	 * @param array $data tmp data
	 */
	private function killParam($name, array &$data)
	{
		if (isset($data[$name])) {
			unset($data[$name]);
		}
	
		foreach ($data as &$val)
		{
			if (is_array($val)) {
				$this->killParam($name, $val);
			}
		}
	}
}
