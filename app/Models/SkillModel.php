<?php
namespace App\Models;

/**
 * Model for the SKILLS table
 *
 *        
 */
class SkillModel implements \JsonSerializable
{

    private $id;

    private $userId;

    private $description;

    private $years;

    private static $rules = [
        'description' => 'required|between:4,32',
        'years' => 'required|integer|min:1'
    ];

    function __construct($id, $userId, $description, $years)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->description = $description;
        $this->years = $years;
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
        return SkillModel::$rules;
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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     *
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
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

    /**
     *
     * @return mixed
     */
    public function getYears()
    {
        return $this->years;
    }

    /**
     *
     * @param mixed $years
     */
    public function setYears($years)
    {
        $this->years = $years;
    }
}