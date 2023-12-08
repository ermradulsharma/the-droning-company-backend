<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavelBox;

class FavelBoxController extends Controller
{
    function index()
    {    
        $boxes = FavelBox::with('favelBoxDetails')->get();
        if($boxes){
            return response()->json([
                'status'=> true,
                'statusCode'=> 200,
                'message' => 'boxes fetch successfully',
                'data'=> $boxes,
            ]);
        }
        else{
            return response()->json([
                'status'=> false,
                'statusCode'=> 404,
                'message' => 'not found',
                'data'=> '',
            ]);
        }
        
    }
}
