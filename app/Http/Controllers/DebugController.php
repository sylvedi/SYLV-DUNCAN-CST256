<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Utility\ILoggerService;

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
        $this->logger->info("Enter DebugController.scratchPad()");
            
            
        $this->logger->info("Exiting DebugController.scratchPad()");
        return view("createGroup");
        
    }
    
}
