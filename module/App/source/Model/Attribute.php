<?php

namespace App\Model;

class Attribute
{
    /**
     * Nombre del atributo
     *
     * @var string
     */
    protected $name;

    /**
     * Valor del atributo
     *
     * @var string
     */
    protected $value;

    /**
     * Retorna el nombre del atributo
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retorna el valor del atributo
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Asigna el nombre del atributo
     *
     * @param string $name
     *
     * @return null
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Asigna el valor del atributo
     *
     * @param string $value
     *
     * @return null
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}