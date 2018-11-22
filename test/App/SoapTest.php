<?php

namespace PlaceToPayTest\App;

use PHPUnit\Framework\TestCase;

class SoapTest extends TestCase
{
    /**
     * Debe retornar la lista de bancos del servicio SOAP
     *
     * getBankList
     *
     * @return null
     */
    public function testObtenerListaDeBancos()
    {
        global $mvc;

        # llamada a la URL de bancos JSON mediante SOAP mediante el router
        $mvc->getRouter()->setIdentifiers('App', 'Soap', 'free');
        $mvc->getRouter()->run();
        $bankList = $mvc->getRouter()->getController()->listaBancos();

        # parsear la lista de bancos y tomar el primero, debe contener las keys bankCode y bankName
        $json = json_decode($bankList["json"]);
        $item = array_shift($json);

        $this->assertObjectHasAttribute("bankCode", $item);
        $this->assertObjectHasAttribute("bankName", $item);
    }

    /**
     * Obtiene la información de una transacción
     *
     * getTransactionInformation
     *
     * @return null
     */
    public function testObtenerInformacionTransaccion()
    {
        global $mvc;

        # llamada a la URL de bancos JSON mediante SOAP mediante el router
        $mvc->getRouter()->setIdentifiers('App', 'Soap', 'free');
        $_GET["transactionID"] = "1464517018";
        $mvc->getRouter()->run();
        $transaction = $mvc->getRouter()->getController()->consultaTransaccion();

        # obtiene el objeto con la información de la transacción, debe contener las keys responseCode, responseReasonText, entre otras
        $json = json_decode($transaction["json"]);

        $this->assertObjectHasAttribute("responseCode", $json);
        $this->assertObjectHasAttribute("responseReasonText", $json);
    }

    /**
     * Crea una transacción y recibe la URL para redirección al banco
     *
     * createTransaction
     *
     * @return null
     */
    public function testCrearTransaccion()
    {
        global $mvc;

        # llamada a la URL de bancos JSON mediante SOAP mediante el router
        $mvc->getRouter()->setIdentifiers('App', 'Pagos', 'free');

        $_SERVER['REQUEST_METHOD']  = "POST";
        $_SERVER["REQUEST_SCHEME"]  = "http";
        $_SERVER["HTTP_HOST"]       = "localhost";
        $_SERVER["SERVER_ADDR"]     = "127.0.0.1";
        $_SERVER["HTTP_USER_AGENT"] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36 OPR/56.0.3051.99";

        $_POST["documentType"]  = "CC";
        $_POST["document"]      = "1110522967";
        $_POST["firstName"]     = "DARÍO ANTONIO";
        $_POST["lastName"]      = "RIVERA TÉLLEZ";
        $_POST["company"]       = "GOOGLE INC.";
        $_POST["emailAddress"]  = "fermius.us@gmail.com";
        $_POST["address"]       = "CRA 9 # 37-A 09 GAITÁN";
        $_POST["city"]          = "MEDELLÍN";
        $_POST["province"]      = "ANTIOQUIA";
        $_POST["country"]       = "CO";
        $_POST["phone"]         = "2724027";
        $_POST["mobile"]        = "3155048715";
        $_POST["bankInterface"] = 0;
        $_POST["bankCode"]      = "1022";
        $_POST["totalAmount"]   = "1250000";
        $_POST["reference"]     = "2675";
        $_POST["description"]   = "Pago PSE - pruebas técnicas";

        $mvc->getRouter()->run();
        $data = $mvc->getRouter()->getController()->crearTransaccion();

        # verificar si existe la url del banco para redirección
        $this->assertArrayHasKey("bankURL", $data);
    }
}