<?php

namespace App\Model;

use SoapClient;

class SoapDriver
{
    /**
     * Objeto SoapClient (modo WSDL)
     *
     * @var SoapClient
     */
    public $soap;

    /**
     * Authentication object
     *
     * @var Authentication
     */
    protected $auth;

    /**
     * Constructor
     */
    public function __construct()
    {
        $wsdl    = "https://test.placetopay.com/soap/pse/?wsdl";
        $login   = "6dd490faf9cb87a9862245da41170ff2";
        $tranKey = "024h1IlD";
        $seed    = date('c');

        $this->auth = new Authentication([
            "login" => $login,
            "seed"  => $seed
        ]);

        $this->auth->setTranKey(sha1($seed . $tranKey, false));
        $this->soap = new SoapClient($wsdl, array('encoding' => "UTF-8"));
    }

    /**
     * Ejecuta la llamada a la función con la autenticación
     *
     * @param string $method
     *
     * @return string
     */
    public function call($method)
    {
        return $this->soap->{$method}(["auth" => $this->auth]);
    }
}