<?php

namespace App\Model;

use Drone\Db\Entity;

class PSETransactionResponse extends Entity
{
    /**
     * Identificar único de la solicitud que originó la respuesta
     *
     * @var string
     */
    public $uniqueRequestId;

    /**
     * Identificador único de la transacción en PlacetoPay
     *
     * @var integer
     */
    public $transactionID;

    /**
     * Identificador único de la sesión en PlacetoPay
     *
     * @var string
     */
    public $sessionID;

    /**
     * Código de respuesta de la transacción, uno de los siguientes valores:
     *
     *      SUCCESS
     *      FAIL_ENTITYNOTEXISTSORDISABLED
     *      FAIL_BANKNOTEXISTSORDISABLED
     *      FAIL_SERVICENOTEXISTS
     *      FAIL_INVALIDAMOUNT
     *      FAIL_INVALIDSOLICITDATE
     *      FAIL_BANKUNREACHEABLE
     *      FAIL_NOTCONFIRMEDBYBANK
     *      FAIL_CANNOTGETCURRENTCYCLE
     *      FAIL_ACCESSDENIED
     *      FAIL_TIMEOUT
     *      FAIL_DESCRIPTIONNOTFOUND
     *      FAIL_EXCEEDEDLIMIT
     *      FAIL_TRANSACTIONNOTALLOWED
     *      FAIL_RISK
     *      FAIL_NOHOST
     *      FAIL_NOTALLOWEDBYTIME
     *      FAIL_ERRORINCREDITS
     *
     * @var string
     */
    public $returnCode;

    /**
     * Código único de seguimiento para la operación dado por la red ACH
     *
     * @var string
     */
    public $trazabilityCode;

    /**
     * Ciclo de compensación de la red
     *
     * @var integer
     */
    public $transactionCycle;

    /**
     * Moneda aceptada por el banco acorde a ISO 4217
     *
     * @var string
     */
    public $bankCurrency;

    /**
     * Factor de conversión de la moneda
     *
     * @var float
     */
    public $bankFactor;

    /**
     * URL a la cual remitir la solicitud para iniciar la interfaz del banco, sólo disponible cuando returnCode = SUCCESS
     *
     * @var string
     */
    public $bankURL;

    /**
     * Estado de la operación en PlacetoPay [ 0 = FAILED, 1 = APPROVED, 2 = DECLINED, 3 = PENDING ]
     *
     * @var integer
     */
    public $responseCode;

    /**
     * Código interno de respuesta de la operación en PlacetoPay
     *
     * @var string
     */
    public $responseReasonCode;

    /**
     * Mensaje asociado con el código de respuesta de la operación en PlacetoPay
     *
     * @var string
     */
    public $responseReasonText;

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setTableName("PSETransactionResponse");
    }
}