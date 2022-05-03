<?php
namespace IEPaySDK;

use IEPaySDK\Lib\Request;
use IEPaySDK\Lib\Signature;

class Payment
{
    public $payType;
    public $signType = 'SHA256';
    public $currency = 'NZD';

    public $apiEndpoint = 'https://mypaynz.com/api/v3/';

    private $mid;
    private $apiKey;

    private $returnUrl;
    private $notifyUrl;
    
    public function __construct(string $payType, string $returnUrl = '', string $notifyUrl = '')
    {
        $this->setMerchantConfig(getenv('MYPAY_ID'), getenv('MYPAY_KEY'));
        $this->returnUrl = empty($returnUrl) ?? getenv('MYPAY_RETURN_URL');
        $this->notifyUrl = empty($notifyUrl) ?? getenv('MYPAY_NOTIFY_URL');
        $this->payType = $payType;
    }

    /**
     * customize mid
     *
     * @param   int      $mid
     */
    public function setMerchantConfig(int $mid, string $apiKey) : Payment
    {
        $this->mid = $mid;
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * make iepay order
     *
     * @param   string  $orderId
     * @param   int     $amount
     * @param   string  $subject
     * @param   string  $detail
     */
    public function payment(string $orderId, int $amount, string $subject, string $detail = '')
    {
        $params = array_merge([
            'amount' => $amount,
            'pay_type' => $this->payType,
            'currency' => $this->currency,
            'out_trade_no' => $orderId,
            'goods' => $subject,
            'goods_detail' => $detail,
            'return_url' => $this->returnUrl,
            'notify_url' => $this->notifyUrl,
            'expired' => 3600,
        ], $this->commomParams());

        $signatureMethod = 'signature' . strtoupper($this->signType);
        $params['sign'] = Signature::getInstance()->$signatureMethod($params, $this->apiKey);

        $url = $this->apiEndpoint . 'payment';
        return Request::request($url, $params, 'post');
    }

    /**
     * Refund an order
     *
     * @param   string  $orderId
     * @param   int     $amount
     */
    public function refund(string $orderId, int $amount = null)
    {
        $params = array_merge([
            'amount' => $amount,
            'out_trade_no' => $orderId,
            'before_charge' => 0,
        ], $this->commomParams());

        $signatureMethod = 'signature' . strtoupper($this->signType);
        $params['sign'] = Signature::getInstance()->$signatureMethod($params, $this->apiKey);
        $url = $this->apiEndpoint . 'refund';

        return Request::request($url, $params, 'post');
    }

    /**
     * Query order status
     *
     * @param   string  $orderId
     */
    public function query(string $orderId)
    {
        $params = array_merge([
            'out_trade_no' => $orderId,
        ], $this->commomParams());

        $signatureMethod = 'signature' . strtoupper($this->signType);
        $params['sign'] = Signature::getInstance()->$signatureMethod($params, $this->apiKey);
        $url = $this->apiEndpoint . 'query';

        return Request::request($url, $params, 'get');
    }

    /**
     * Common param
     */
    private function commomParams() : array
    {
        return [
            'mid' => $this->mid,
            'sign_type' => $this->signType,
        ];
    }

    /**
     * Generate object
     */
    public static function make(...$params)
    {
        return new static(...$params);
    }    
}