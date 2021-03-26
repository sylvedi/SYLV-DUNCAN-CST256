<?php
namespace App\Models;

/**
 * Model for the USERS table with only required registration data (includes CREDENTIALS data)
 *
 *        
 */
class RegistrationModel implements \JsonSerializable
{

    private $id;

    private $username;

    private $password;

    private $firstname;

    private $lastname;

    private $email;

    private $city;

    private $state;

    private static $rules = [
        'username' => 'required|string|between:4,16',
        'password' => 'required|between:8,128',
        'firstname' => 'required|string|max:32',
        'lastname' => 'required|string|max:32',
        'email' => 'required|email|between:5,128',
        'city' => 'required|string|max:32',
        'state' => 'required|string|size:2'
    ];

    public function __construct($id, $username, $password, $firstname, $lastname, $email, $city, $state)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->city = $city;
        $this->state = $state;
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
        return RegistrationModel::$rules;
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
    public function getUsername()
    {
        return $this->username;
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
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
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
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     *
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     *
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     *
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     *
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     *
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

}
