<?php

namespace IEPaySDK\Lib;

class Signature
{
    /**
     * instance of this class
     *
     * @var Signature
     */
    public static $instance;

    /**
     * Get singleton instance
     *
     * @return Signature
     */
    public static function getInstance()
    {
        if(self::$instance == null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){}

    /**
     * MD5 signature
     *
     * @param   array   $params
     * @param   string  $apiKey
     */
    public function signatureMD5(array $params, string $apiKey)
    {
        $params = $this->filterRequiredColumn($params);
        $paramString = $this->buildEncryptString($params, $apiKey);
        return md5($paramString);
    }

    /**
     * SHA256 signature
     *
     * @param   array   $params
     * @param   string  $apiKey
     */
    public function signatureSHA256(array $params, string $apiKey)
    {
        $params = $this->filterRequiredColumn($params);
        $paramString = $this->buildEncryptString($params, $apiKey);
        return hash('sha256', $paramString);
    }

    public function buildEncryptString(array $params, string $apiKey)
    {
        ksort($params);
        foreach($params as $key => $val)
        {
            $pram[] = $key . '=' . $val;
        }
        return implode('&', $pram) . $apiKey;
    }

    /**
     * column filter
     * 
     * @param   array  $params
     */
    private function filterRequiredColumn(array $params)
    {
        $requiredColumn = [
            'mid', 'total_fee', 'currency', 'goods', 'goods_detail', 'out_trade_no', 'pay_type',
            'tid', 'amount', 'return_url', 'notify_url', 'fail_url', 'expired', 'before_charge'
        ];
        foreach($params as $key => $val)
        {
            if(in_array($key, $requiredColumn))
            {
                $response[$key] = $val;
            }
        }
        return $response;
    }
}