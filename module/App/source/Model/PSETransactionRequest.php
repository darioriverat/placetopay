<?php

namespace App\Model;

use Drone\Db\Entity;

class PSETransactionRequest extends Entity
{
    /**
     * Código de la entidad financiera con la cual realizar la transacción
     *
     * @var string
     */
    public $bankCode;

    /**
     * Tipo de interfaz del banco a desplegar [0 = PERSONAS, 1 = EMPRESAS]
     *
     * @var string
     */
    public $bankInterface;

    /**
     * URL de retorno especificada para la entidad financiera
     *
     * @var string
     */
    public $returnURL;

    /**
     * Referencia única de pago
     *
     * @var string
     */
    public $reference;

    /**
     * Descripción del pago
     *
     * @var string
     */
    public $description;

    /**
     * Idioma esperado para las transacciones acorde a ISO 631-1, mayúscula sostenida
     *
     * @var string
     */
    public $language;

    /**
     * Moneda a usar para el recaudo acorde a ISO 4217
     *
     * @var string
     */
    public $currency;

    /**
     * Valor total a recaudar
     *
     * @var double
     */
    public $totalAmount;

    /**
     * Discriminación del impuesto aplicado
     *
     * @var double
     */
    public $taxAmount;

    /**
     * Base de devolución para el impuesto
     *
     * @var double
     */
    public $devolutionBase;

    /**
     * Propina u otros valores exentos de impuesto (tasa aeroportuaria) y que deben agregarse al valor total a pagar
     *
     * @var double
     */
    public $tipAmount;

    /**
     * Información del pagador
     *
     * @var Person
     */
    public $payer;

    /**
     * Información del comprador
     *
     * @var Person
     */
    public $buyer;

    /**
     * Información del receptor
     *
     * @var Person
     */
    public $shipping;

    /**
     * Dirección IP desde la cual realiza la transacción el pagador
     *
     * @var string
     */
    public $ipAddress;

    /**
     * Agente de navegación utilizado por el pagador
     *
     * @var string
     */
    public $userAgent;

    /**
     * Datos adicionales para ser almacenados con la transacción
     *
     * @var Attribute[]
     */
    public $additionalData;

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setTableName("PSETransactionRequest");
    }

    /**
     * Agrega un atributo adicional
     *
     * @param Attribute $attr
     *
     * @return null
     */
    public function addAditional(Attribute $attr)
    {
        $this->additional[] = $attr;
    }
}