<?php

namespace App\Http\Controllers;
use App\Models\color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    public function addColor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'color' => 'required|string',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $color = new color();
        $color->color = $request->input('color');
        $color->save();

        return response()->json(['success'], 200);
    }

    function displayColor(){
        return color::all();
    
    }
    
}
