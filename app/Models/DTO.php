<?php
namespace App\Models;

class DTO implements \JsonSerializable
{
    private $status;
    private $message;
    private $data;
    
    
    public function __construct($status, $message, $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

