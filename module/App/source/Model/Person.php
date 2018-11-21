<?php

namespace App\Model;

use Drone\Db\Entity;

class Person extends Entity
{
    /**
     * Número de identificación de la persona
     *
     * @var string
     */
    public $document;

    /**
     * Tipo de documento de identificación de la persona [CC, CE, TI, PPN].
     *
     * @var string
     */
    public $documentType ;

    /**
     * Nombres
     *
     * @var string
     */
    public $firstName;

    /**
     * Apellidos
     *
     * @var string
     */
    public $lastName;

    /**
     * Nombre de la compañía en la cual labora o representa
     *
     * @var string
     */
    public $company;

    /**
     * Correo electrónico
     *
     * @var string
     */
    public $emailAddress;

    /**
     * Dirección postal completa
     *
     * @var string
     */
    public $address;

    /**
     * Nombre de la ciudad coincidente con la dirección
     *
     * @var string
     */
    public $city;

    /**
     * Nombre de la provincia o departamento coincidente con la dirección
     *
     * @var string
     */
    public $province;

    /**
     * Código internacional del país que aplica a la dirección física acorde a ISO 3166-1, mayúscula sostenida
     *
     * @var string
     */
    public $country;

    /**
     * Número de telefonía fija
     *
     * @var string
     */
    public $phone;

    /**
     * Número de telefonía móvil o celular
     *
     * @var string
     */
    public $mobile;

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setTableName("Person");
    }
}