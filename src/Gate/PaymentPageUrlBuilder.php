<?php

namespace Gate;

/**
 * Payment page URL Builder 
 * 
 * @package 
 * @copyright it ecommpay
 * @author Dmitry Fedorov <d.fedorov@it.ecommpay.com> 
 */
class PaymentPageUrlBuilder
{
	const PAYMENT_URL = 'https://paymentpage.ecommpay.com/payment?';

	/**
	 * Signature Handler 
	 * 
	 * @var SignatureHandler $signatureHandler
	 */
	private $signatureHandler;

	/**
	 * __construct 
	 * 
	 * @param SignatureHandler $signatureHandler 
	 */
	public function __construct(SignatureHandler $signatureHandler)
	{
		$this->signatureHandler = $signatureHandler;
	}

	/**
	 * Get Url 
	 * 
	 * @param array $params Params
	 * @return string
	 */
	public function getUrl(array $params)
	{
		$signature = $this->signatureHandler->sign($params);
		return self::PAYMENT_URL . http_build_query($params) . '&signature=' . $signature;
	}
}
