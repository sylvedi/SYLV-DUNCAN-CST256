<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebugController extends Controller
{
        private $logger;
    
    /**
     * Instantiates the object with a database connection
     *
     * @param ILoggerService $logger
     */
    public function __construct(ILoggerService $logger)
    {
        $this->logger = $logger;
    }
    
    
    public function scratchPad(Request $request){
        
        return view("createGroup");
        
    }
    
}
