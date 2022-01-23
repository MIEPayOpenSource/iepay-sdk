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
        $this->mid = getenv('MYPAY_ID');
        $this->apiKey = getenv('MYPAY_KEY');
        $this->returnUrl = !empty($returnUrl) ? $returnUrl : getenv('MYPAY_RETURN_URL');
        $this->notifyUrl = !empty($notifyUrl) ? $notifyUrl : getenv('MYPAY_NOTIFY_URL');
        $this->payType = $payType;

        if(getenv('MYPAY_MODE') == 'test')
        {
            $this->apiEndpoint = 'https://local.miepay.xyz/api/v3/';
        }
    }

    public function payment($orderId, $amount, $title, $detail = '')
    {
        $params = array_merge([
            'amount' => $amount,
            'pay_type' => $this->payType,
            'currency' => $this->currency,
            'out_trade_no' => $orderId,
            'goods' => $title,
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

    public function refund($orderId, $amount = null)
    {
        $params = array_merge([
            'amount' => $amount,
            'out_trade_no' => $orderId,
            'before_charge' => false,
        ], $this->commomParams());

        $signatureMethod = 'signature' . strtoupper($this->signType);
        $params['sign'] = Signature::getInstance()->$signatureMethod($params, $this->apiKey);
        $url = $this->apiEndpoint . 'refund';

        return Request::request($url, $params, 'post');
    }

    public function query($orderId)
    {
        $params = array_merge([
            'out_trade_no' => $orderId,
        ], $this->commomParams());

        $signatureMethod = 'signature' . strtoupper($this->signType);
        $params['sign'] = Signature::getInstance()->$signatureMethod($params, $this->apiKey);
        $url = $this->apiEndpoint . 'query';

        return Request::request($url, $params, 'get');
    }

    private function commomParams() : array
    {
        return [
            'mid' => $this->mid,
            'sign_type' => $this->signType,
        ];
    }

    /**
     * generate object
     */
    public static function make(...$params)
    {
        return new static(...$params);
    }    
}