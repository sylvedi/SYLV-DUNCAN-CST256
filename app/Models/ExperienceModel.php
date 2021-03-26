<?php
namespace App\Models;

/**
 * Model for the EXPERIENCE table
 *
 *        
 */
class ExperienceModel implements \JsonSerializable
{

    private $id;

    private $userid;

    private $company;

    private $jobtitle;

    private $description;

    private $startdate;

    private $enddate;

    private $currentjob;

    private static $rules = [
        'company' => 'required|max:128',
        'jobtitle' => 'required|max:128',
        'description' => 'required|max:512',
        'startdate' => 'required|date',
        'enddate' => 'required_if:currentjob,false|date'
    ];

    function __construct($id, $userid, $company, $jobtitle, $description, $startdate, $enddate, $currentjob)
    {
        $this->id = $id;
        $this->userid = $userid;
        $this->company = $company;
        $this->jobtitle = $jobtitle;
        $this->description = $description;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $this->currentjob = $currentjob;
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
        return ExperienceModel::$rules;
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
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     *
     * @param mixed $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     *
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     *
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     *
     * @return mixed
     */
    public function getJobtitle()
    {
        return $this->jobtitle;
    }

    /**
     *
     * @param mixed $jobtitle
     */
    public function setJobtitle($jobtitle)
    {
        $this->jobtitle = $jobtitle;
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
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     *
     * @param mixed $startdate
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
    }

    /**
     *
     * @return mixed
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     *
     * @param mixed $enddate
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    }

    /**
     *
     * @return mixed
     */
    public function getCurrentjob()
    {
        return $this->currentjob;
    }

    /**
     *
     * @param mixed $currentjob
     */
    public function setCurrentjob($currentjob)
    {
        $this->currentjob = $currentjob;
    }
}

