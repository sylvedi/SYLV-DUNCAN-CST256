<?php
namespace App\Models;

/**
 * Model for the CREDENTIALS table
 *
 *        
 */
class LoginModel implements \JsonSerializable
{

    private $id;

    private $userId;
    
    private $username;

    private $password;

    private static $rules = [
        'username' => 'required|string|between:4,16',
        'password' => 'required|between:8,128'
    ];

    function __construct($id, $userId, $username, $password)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->username = $username;
        $this->password = $password;
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
        return LoginModel::$rules;
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
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     *
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     *
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
}

