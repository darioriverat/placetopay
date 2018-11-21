<?php

namespace App\Controller;

use Drone\Mvc\AbstractionController;
use Drone\Db\TableGateway\EntityAdapter;
use Drone\Db\TableGateway\TableGateway;
use Drone\Dom\Element\Form;
use Drone\Network\Http;
use Drone\Validator\FormValidator;
use App\Model\Person;
use App\Model\PSETransactionRequest;
use App\Model\PSETransactionResponse;
use App\Model\SoapDriver;

class Pagos extends AbstractionController
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
     * Muestra el formulario de pagos
     *
     * @return array
     */
    public function index()
    {
        return [];
    }

    /**
     * Realiza una petición de transacción
     *
     * @return array
     */
    public function crearTransaccion()
    {
        $this->setTerminal(true);
        $post = $this->getPost();

        # datos para enviar a la vista
        $data = [];

        try {

            # Validación de parámetros del formulario, deben llegar obligatoriamente los descritos a continuación
            $needles = [
                'documentType', 'document', 'firstName', 'lastName', 'company', 'emailAddress', 'address', 'country',
                'province', 'city', 'phone', 'mobile', 'bankInterface', 'bankCode', 'totalAmount', 'reference', 'description'
            ];

            array_walk($needles, function(&$item) use ($post) {
                if (!array_key_exists($item, $post))
                {
                    $http = new Http();
                    $http->writeStatus($http::HTTP_BAD_REQUEST);

                    die('Error ' . $http::HTTP_BAD_REQUEST .' (' . $http->getStatusText($http::HTTP_BAD_REQUEST) . ')!!');
                }
            });

            /**
             * Validaciones sobre los campos del formulario
             */

            $components = [
                "attributes" => [
                    "documentType" => [
                        "required"  => true,
                    ],
                    "document" => [
                        "required"  => true,
                        "maxlength" => 12,
                    ],
                    "firstName" => [
                        "required"  => true,
                        "maxlength" => 60,
                    ],
                    "lastName" => [
                        "required"  => true,
                        "maxlength" => 60,
                    ],
                    "company" => [
                        "required"  => true,
                        "maxlength" => 60,
                    ],
                    "emailAddress" => [
                        "required"  => true,
                        "type"      => "email",
                        "maxlength" => 80,
                    ],
                    "address" => [
                        "required"  => true,
                        "maxlength" => 100,
                    ],
                    "country" => [
                        "required"  => true,
                    ],
                    "province" => [
                        "required"  => true,
                    ],
                    "city" => [
                        "required"  => true,
                    ],
                    "phone" => [
                        "required"  => true,
                        "maxlength" => 30,
                    ],
                    "mobile" => [
                        "required"  => true,
                        "maxlength" => 30,
                    ],
                    "bankInterface" => [
                        "required"  => true,
                    ],
                    "totalAmount" => [
                        "required"  => true,
                        "type"      => "number"
                    ],
                    "reference" => [
                        "required"  => true,
                    ],
                    "description" => [
                        "required"  => true,
                    ],
                ],
            ];

            $options = [
                "documentType" => [
                    "label"      => "Tipo de documento",
                    "validators" => [
                        "InArray"  => ["haystack" => ['CC', 'CE', 'TI', 'PPN']]
                    ]
                ],
                "document" => [
                    "label"      => "Número de documento",
                    "validators" => [
                        "Alnum"  => ["allowWhiteSpace" => false]
                    ],
                ],
                "firstName" => [
                    "label"      => "Nombre",
                    "validators" => [
                        "Alnum"  => ["allowWhiteSpace" => true]
                    ],
                ],
                "lastName" => [
                    "label"      => "Apellidos",
                    "validators" => [
                        "Alnum"  => ["allowWhiteSpace" => true]
                    ],
                ],
                "company" => [
                    "label"      => "Empresa para la que labora",
                ],
                "emailAddress" => [
                    "label"      => "Dirección de correo electrónico",
                ],
                "address" => [
                    "label"      => "Dirección",
                ],
                "country" => [
                    "label"      => "País",
                    "validators" => [
                        "InArray"  => ["haystack" => ['CO']]
                    ]
                ],
                "province" => [
                    "label"      => "Provincia",
                ],
                "city" => [
                    "label"      => "Ciudad",
                ],
                "phone" => [
                    "label"      => "Número de teléfono fijo",
                ],
                "mobile" => [
                    "label"      => "Número de teléfono móvil",
                ],
                "bankInterface" => [
                    "label"      => "Tipo de cliente",
                    "validators" => [
                        "InArray"  => ["haystack" => [0, 1]]
                    ]
                ],
                "totalAmount" => [
                    "label"      => "Valor total recaudo",
                ],
                "reference" => [
                    "label"      => "Referencia de pago",
                ],
                "description" => [
                    "label"      => "Descripción del pago"
                ],
            ];

            $form = new Form($components);
            $form->fill($post);

            $validator = new FormValidator($form, $options);
            $validator->validate();

            $data["validator"] = $validator;

            if (!$validator->isValid())
            {
                $data["messages"] = $validator->getMessages();
                throw new \Drone\Exception\Exception("Errores de validación del formulario");
            }

            # pagador / comprador / receptor
            $person = new Person();
            $person->exchangeArray([
                "document"     => $post["document"],
                "documentType" => $post["documentType"],
                "firstName"    => $post["firstName"],
                "lastName"     => $post["lastName"],
                "company"      => $post["company"],
                "emailAddress" => $post["emailAddress"],
                "address"      => $post["address"],
                "city"         => $post["city"],
                "province"     => $post["province"],
                "country"      => $post["country"],
                "phone"        => $post["phone"],
                "mobile"       => $post["mobile"],
            ]);

            # id único de solicitud
            $uniqid = base64_encode(uniqid() . time());
            $uniqid = str_replace("=", "", $uniqid);

            # solicitud de transacción
            $pseTran = new PSETransactionRequest();
            $pseTran->exchangeArray([
                "bankCode"       => $post["bankCode"],
                "bankInterface"  => $post["bankInterface"],
                "returnURL"      => $_SERVER["REQUEST_SCHEME"] . '://'. $_SERVER["HTTP_HOST"] . $this->getBasePath() . "/App/Pagos/confirmacion/uniqueRequestId/$uniqid",
                "reference"      => $post["reference"],
                "description"    => $post["description"],
                "language"       => "ES",
                "currency"       => "COP",
                "totalAmount"    => $post["totalAmount"],
                "taxAmount"      => 0,
                "devolutionBase" => 0,
                "tipAmount"      => 0,
                "payer"          => $person,
                "buyer"          => $person,
                "shipping"       => $person,
                "ipAddress"      => $_SERVER["SERVER_ADDR"],
                "userAgent"      => $_SERVER['HTTP_USER_AGENT'],
            ]);

            $soap = new SoapDriver();
            $result = $soap->call("createTransaction", $pseTran);

            if (is_object($result))
            {
                $tranRes = $result->createTransactionResult;

                if ($tranRes->returnCode == "SUCCESS")
                {
                    # guardar respuesta de la transacción en la bd
                    $pseResponse = new PSETransactionResponse();
                    $pseResponse->exchangeArray([
                        "uniqueRequestId"    => $uniqid,
                        "transactionID"      => $tranRes->transactionID,
                        "sessionID"          => $tranRes->sessionID,
                        "returnCode"         => $tranRes->returnCode,
                        "trazabilityCode"    => $tranRes->trazabilityCode,
                        "transactionCycle"   => $tranRes->transactionCycle,
                        "bankCurrency"       => $tranRes->bankCurrency,
                        "bankFactor"         => $tranRes->bankFactor,
                        "bankURL"            => $tranRes->bankURL,
                        "responseCode"       => $tranRes->responseCode,
                        "responseReasonCode" => $tranRes->responseReasonCode,
                        "responseReasonText" => $tranRes->responseReasonText
                    ]);

                    $this->getPSETransactionResponseAdapter()->insert($pseResponse);

                    $data["bankURL"] = $tranRes->bankURL;
                }
                else
                    throw new \Exception($tranRes->sessionID->responseReasonText);
            }

            $data["process"] = "success";
        }
        catch (\Drone\Exception\Exception $e)
        {
            # ERROR-MESSAGE
            $data["process"] = "warning";
            $data["message"] = $e->getMessage();
        }
        catch (\Exception $e)
        {
            $data["code"]    = $e->getCode();
            $data["message"] = $e->getMessage();
            $data["process"] = "warning";

            # verifica el etorno de la app, si está en modo dev o producción
            $config = include 'config/application.config.php';
            $data["dev_mode"] = $config["environment"]["dev_mode"];

            # redirección a la vista de errores
            $this->setMethod('error');

            return $data;
        }

        return $data;
    }

    /**
     * Muestra el estado de una transacción realizada
     *
     * @return array
     */
    public function confirmacion()
    {
        # datos para enviar a la vista
        $data = [];

        try {

            $rowset = $this->getPSETransactionResponseAdapter()->select([
                "uniqueRequestId" => $_GET["uniqueRequestId"]
            ]);

            if (!count($rowset))
                throw new \Exception("Error al recuperar la transacción");

            $tran = array_shift($rowset);

            $data["transaccion"] = $tran;
        }
        catch (\Exception $e)
        {
            $data["code"]    = $e->getCode();
            $data["message"] = $e->getMessage();
            $data["process"] = "warning";

            # verifica el etorno de la app, si está en modo dev o producción
            $config = include 'config/application.config.php';
            $data["dev_mode"] = $config["environment"]["dev_mode"];

            # redirección a la vista de errores
            $this->setMethod('error');

            return $data;
        }

        return $data;
    }
}