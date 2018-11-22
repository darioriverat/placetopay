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
}