<?php
namespace App\Models;

/**
 * Model for the JOBS table
 *
 *        
 */
class JobModel implements \JsonSerializable
{

    private $id;

    private $companyId;

    private $title;

    private $description;

    private static $rules = [
        'title' => 'required|between:5,64',
        'description' => 'required|between:5,512'
    ];

    function __construct($id, $companyId, $title, $description)
    {
        $this->id = $id;
        $this->companyId = $companyId;
        $this->title = $title;
        $this->description = $description;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     *
     * @return multitype:string
     */
    public static function getRules()
    {
        return JobModel::$rules;
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
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     *
     * @param mixed $companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}

