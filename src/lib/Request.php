<?php

namespace IEPaySDK\Lib;

class Request
{
    /**
     * Send CURL request
     *
     * @param string $url Main url to request
     * @param mixed $params Request params
     * @param string $method get|post
     * @param boolean $isHttps Is a https request
     * @param Array $header Header array
     * @param string $format json|xml|origin
     */
    public static function request(string $url, $params = [], string $method = 'get', bool $isHttps = true, Array $header = [],  string $format = 'json')
    {
        $method = strtolower($method);

        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($isHttps) 
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        if($method == 'post')
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } 
        else 
        {
            if($params) 
            {
                if(is_array($params)) 
                {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } 
            else 
            {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        if(!empty($header))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $response = curl_exec($ch);

        if($response === false) 
        {
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);

        if($format == "json")
        {
            return json_decode($response, true);
        }
        if($format == "xml")
        {
            return simplexml_load_string($response);
        }

        return $response;
    }
}