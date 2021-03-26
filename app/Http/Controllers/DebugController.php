<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebugController extends Controller
{
    
    public function scratchPad(Request $request){
        
        return view("createGroup");
        
    }
    
}
