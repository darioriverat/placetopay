<?php

namespace App\Controller;

use App\Model\Authentication;
use App\Model\PSETransactionRequest;
use App\Model\SoapDriver;
use Drone\Mvc\AbstractionController;
use Drone\Db\TableGateway\EntityAdapter;
use Drone\Db\TableGateway\TableGateway;
use App\Model\PSETransactionResponse;

class Soap extends AbstractionController
{
    /**
     * @var EntityAdapter
     */
    private $PSETransactionResponseAdapter;

    /**
     * @return EntityAdapter
     */
    private function getPSETransactionResponseAdapter()
    {
        if (!is_null($this->PSETransactionResponseAdapter))
            return $this->PSETransactionResponseAdapter;

        $this->PSETransactionResponseAdapter = new EntityAdapter(new TableGateway(new PSETransactionResponse()));

        return $this->PSETransactionResponseAdapter;
    }

    /**
     * Imprime la lista de bancos del web service en JSON
     *
     * @return array
     */
    public function listaBancos()
    {
        $this->setTerminal(true);

        try {

            # verificar cache
            $bankList = json_decode(file_get_contents("cache/bankList.json"));
            $cachedKey = date("Ymd");

            if (!property_exists($bankList, $cachedKey))
            {
                $soap = new SoapDriver();
                $result = $soap->call("getBankList");
                $result = $result->getBankListResult->item;

                file_put_contents("cache/bankList.json", json_encode([$cachedKey => $result]));
            }
            else
                $result = $bankList->$cachedKey;

            $json = json_encode($result);
        }
        catch (\Exception $e)
        {
            $json = json_encode(["error" => $e->getMessage()]);
        }

        echo $json;

        return ["json" => $json];
    }

    /**
     * Obtiene la información relativa a una transacción desde el web service, si
     * la transacción ha cambiado de estado la actualiza localmente
     *
     * @return array
     */
    public function consultaTransaccion()
    {
        $this->setTerminal(true);

        try {

            $soap = new SoapDriver();
            $result = $soap->call("getTransactionInformation", $_GET["transactionID"]);

            $tranRes = $result->getTransactionInformationResult;

            # verificar si ya se actualizó localmente
            $rowset = $this->getPSETransactionResponseAdapter()->select([
                "transactionID" => $_GET["transactionID"]
            ]);

            if (!count($rowset))
                throw new \Exception("Error al recuperar la transacción");

            $localTran = array_shift($rowset);

            $changed = false;

            if ($localTran->responseCode !== $tranRes->responseCode)
            {
                $localTran->exchangeArray([
                    "responseCode"       => $tranRes->responseCode,
                    "responseReasonText" => $tranRes->responseReasonText
                ]);

                $this->getPSETransactionResponseAdapter()->update($localTran, ["uniqueRequestId" => $localTran->uniqueRequestId]);
                $changed = true;
            }

            $result->changed = $changed;

            $json = json_encode($result);
        }
        catch (\Exception $e)
        {
            $json = json_encode(["error" => $e->getMessage()]);
        }

        return ["json" => $json];
    }

    /**
     * Método para liberar el controlador de la ejecución inicial (initExecution == true)
     *
     * @return array
     */
    public function free()
    {
        $this->setTerminal(true);
        return [];
    }
}