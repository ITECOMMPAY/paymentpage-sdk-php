<?php

namespace Gate;

use System\Config;

/**
 * Gate 
 * 
 * @package 
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com> 
 */
class Gate
{
	//Currency codes
	const CURRENCY_RUB = 'RUB';
	const CURRENCY_USD = 'USD';
	const CURRENCY_EUR = 'EUR';

	/**
	 * Merchant config
	 * 
	 * @var \System\Config $config
	 */
	private $config;
	
	/**
	 * Some additional params
	 * 
	 * @var array $additionalParams
	 */
	private $additionalParams;
	
	/**
	 * Builder for Payment page 
	 * 
	 * @var PaymentPageUrlBuilder $paymentPageUrlBuilder
	 */
	private $paymentPageUrlBuilder;

	/**
	 * Signature Handler (check, sign) 
	 * 
	 * @var SignatureHandler $signatureHandler
	 */
	private $signatureHandler;

	/**
	 * cURL Sender 
	 * 
	 * @var Sender $sender
	 */
	private $sender;

	/**
	 * __construct 
	 * 
	 * @param string $configPath Custom config route, CONFIG_PATH const as default
	 * @param array $additionalParams May be some addiotional params, who cares :)
	 */
	public function __construct($configPath = CONFIG_PATH, array $additionalParams = array())
	{
		$this->config = Config::fromFile($configPath);
		$this->signatureHandler = new SignatureHandler($this->config['secretKey']);
		$this->paymentPageUrlBuilder = new PaymentPageUrlBuilder($this->signatureHandler);
		$this->sender = new Sender();
		$this->additionalParams = $additionalParams;
	}

	/**
	 * Get URL for purchase payment page 
	 * 
	 * @param string $project_id Merchant Project ID
	 * @param string $payment_id Merchant Payment ID
	 * @param string $payment_amount Payment amount
	 * @param string $payment_currency Currency code (RUB as default)
	 * @param string $customer_id Some customer's ID (phone, email, blood test etc)
	 * @param string $payment_description Payment description
	 * @param string $language_code Language code
	 * @return string
	 */
	public function getPurchasePaymentPageUrl($project_id, $payment_id, $payment_amount, $payment_currency = self::CURRENCY_RUB, $customer_id = '', $payment_description = '', $language_code = '')
	{
		$params = array();
		foreach (array(
			'project_id' => true, 
			'payment_id' => true, 
			'payment_currency' => true, 
			'payment_amount' => true, 
			'customer_id' => false, 
			'payment_description' => false, 
			'language_code' => false,
		) as $name => $required) {
			$params[$name] = array('value' => $$name, 'required' => $required);
		}
		
		$this->preparePaymentPageParams($params);
		return $this->paymentPageUrlBuilder->getUrl($params);
	}

	/**
	 * Get URL for tokenize payment page 
	 * 
	 * @param string $project_id Merchant Project ID
	 * @param string $mode Mode
	 * @param string $customer_id Some customer's ID (phone, email, blood test etc)
	 * @param string $payment_description Payment description
	 * @param string $language_code Payment description
	 * @return string
	 */
	public function getTokenizePaymentPageUrl($project_id, $mode, $customer_id, $payment_description = '', $language_code = '')
	{
		$params = array();
		foreach (array(
			'project_id' => true,
			'mode' => true,
			'customer_id' => true,
			'payment_description' => false,
			'language_code' => false,
		) as $name => $required) {
			$params[$name] = array('value' => $$name, 'required' => $required);
		}
		
		$this->preparePaymentPageParams($params);
		return $this->paymentPageUrlBuilder->getUrl($params);
	}

	/**
	 * Send Request to EGate 
	 * 
	 * @param string $paymentId Merchant Payment ID
	 */
	public function sendRequest($paymentId)
	{
		$this->sender->send(new Request($paymentId));
	}
	
	/**
	 * Callback handler 
	 * 
	 * @param string $rawData RAW string data from Gate
	 * @return Callback
	 */
	public function handleCallback($rawData)
	{
		return new Callback($rawData, $this->signatureHandler);
	}

	/**
	 * Prepating params for Payment page 
	 * 
	 * @param array $params Params
	 * @throws ProcessException
	 */
	private function preparePaymentPageParams(array &$params)
	{
		foreach ($params as $name => $valueBag) {
			if ($valueBag['required'] && !$valueBag['value']) {
				throw new ProcessException("Required Param: '{$name}' is empty");
			}

			if (!is_string($valueBag['value'])) {
				$type = gettype($valueBag['value']);
				throw new ProcessException("Param: '{$name}' has to be type of 'string'. Type: {$type} given");
			}
			
			if ($valueBag['value']) {
				$params[$name] = $valueBag['value'];
			} else {
				unset($params[$name]);
			}
		}
	}
}
