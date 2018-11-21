<?php

namespace App\Model;

class Authentication
{
    /**
     * Identificador habilitado para el consumo del API, entregado por Place to Pay
     *
     * @var string
     */
    protected $login;

    /**
     * Llave transaccional para el consumo del API SHA1(seed + tranKey)
     *
     * @var string
     */
    protected $tranKey;

    /**
     * Semilla usada para el consumo del API en el proceso del hash por SHA1 del tranKey, ISO 8601
     *
     * @var string
     */
    protected $seed;

    /**
     * Datos adicionales a la estructura de autenticación
     *
     * @var Attribute[]
     */
    protected $additional;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options)
    {
    	# asignación dinámica de atributos
        foreach ($options as $option => $value)
        {
            if (property_exists(__CLASS__, $option) && method_exists($this, 'set'.$option))
                $this->{'set'.$option}($value);
        }
    }

    /**
     * Retorna el identificador para el consumo de la API
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Retorna la llave para el consumo de la API
     *
     * @return string
     */
    public function getTranKey()
    {
        return $this->tranKey;
    }

    /**
     * Retorna la semilla usada
     *
     * @return string
     */
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * Retorna los atributos adicionales
     *
     * @return Attribute[]
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * Asigna el identificador para el consumo de la API
     *
     * @param string $value
     *
     * @return null
     */
    public function setLogin($value)
    {
        $this->login = $value;
    }

    /**
     * Asigna la llave para el consumo de la API
     *
     * @param string $value
     *
     * @return null
     */
    public function setTranKey($value)
    {
        $this->tranKey = $value;
    }

    /**
     * Asigna la semilla a utilizar
     *
     * @param string $value
     *
     * @return null
     */
    public function setSeed($value)
    {
        $this->seed = $value;
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