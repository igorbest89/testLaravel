<?php

namespace App\Api\Shopify;


class Shopify
{
    private $key;
    private $password;
    private $hostname;
    private $version;

    public $url;

    public function __construct($key, $password, $hostname = 'intentlaravel1.myshopify.com', $version = '2019-07')
    {
        $this->key      = $key;
        $this->password = $password;
        $this->hostname = $hostname;
        $this->version  = $version;

        $this->initUrl();
    }

    private function initUrl()
    {
        $this->url = 'https://' . $this->getAccessKey() . '@' . $this->hostname . '/admin/api/' . $this->version . '/' ;
    }

    private function initRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($output);

        return $response;
    }

    private function getAccessKey()
    {
        return $this->key . ':' . $this->password;
    }

    public function getCustomers()
    {
        $url = $this->url . 'customers.json';

        return $this->initRequest($url);
    }

}
