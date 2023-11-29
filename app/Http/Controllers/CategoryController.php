<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string',
            'image' =>'required|image', // Update the validation rule for the image
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $request->all()], 422);
        }

        $category = new Category();
        $category->category = $request->input('category');

        $category->image=$request->file('image')->store('productImages');

        $category->save();

        return response()->json(['success'], 200);
    }
    
    public function displayCategory()
    {
        return Category::all();
    }
}