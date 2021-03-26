<?php
namespace App\Models;

/**
 * Model for the GROUPS table. Includes USER_GROUPS data.
 *
 *        
 */
class GroupModel implements \JsonSerializable
{

    private $id;
    
    private $adminId;

    private $name;

    private $description;

    private $members;
    
    private static $rules = [
        'name' => 'required|between:3,32',
        'description' => 'max:255'
    ];

    function __construct($id, $adminId, $name, $description, $members=array())
    {
        $this->id = $id;
        $this->adminId = $adminId;
        $this->name = $name;
        $this->description = $description;
        $this->members = $members;
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
        return GroupModel::$rules;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * @param mixed $adminId
     */
    public function setAdminId($adminId)
    {
        $this->adminId = $adminId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param mixed $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }



}