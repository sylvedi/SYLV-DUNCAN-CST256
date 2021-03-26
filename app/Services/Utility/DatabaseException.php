<?php
namespace App\Services\Utility;

use Exception;

/**
 * Generic wrapper for database exceptions
 *
 *        
 */
class DatabaseException extends Exception
{

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

