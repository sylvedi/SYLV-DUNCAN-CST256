<?php
namespace App\Models;

/**
 * Model for the COMPANIES table
 *
 *        
 */
class CompanyModel
{

    private $id;

    private $name;

    function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}

