<?php
namespace App\Models;

/**
 * Model for the EDUCATION table
 *
 *        
 */
class EducationModel implements \JsonSerializable
{

    private $id;

    private $userId;

    private $school;

    private $description;

    private static $rules = [
        'school' => 'required|min:3|max:64',
        'description' => 'required|max:128'
    ];

    function __construct($id, $userId, $school, $description)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->school = $school;
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
        return EducationModel::$rules;
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
    public function getSchool()
    {
        return $this->school;
    }

    /**
     *
     * @param mixed $school
     */
    public function setSchool($school)
    {
        $this->school = $school;
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

