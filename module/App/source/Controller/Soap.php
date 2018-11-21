<?php

namespace App\Controller;

use App\Model\Authentication;
use App\Model\SoapDriver;
use Drone\Mvc\AbstractionController;

class Soap extends AbstractionController
{
    /**
     * Instancia de Authentication
     *
     * @var Authentication
     */
    protected $auth;

    public function listaBancos()
    {
        $this->setTerminal(true);

        try {

            $soap = new SoapDriver();
            $result = $soap->call("getBankList");
            $result = $result->getBankListResult->item;

            echo json_encode($result);
        }
        catch (\Exception $e)
        {
            echo json_encode(["error" => $e->getMessage()]);
        }

        return [];
    }
}