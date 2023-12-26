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
    
    public function updateCategory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'image' =>'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
        $category->category = $request->input('category');
    
        if ($request->hasFile('image')) {
            // Delete the previous image if needed
            Storage::delete($category->image);
    
            $category->image = $request->file('image')->store('productImages');
        }
    
        $category->save();
    
        return response()->json(['success'], 200);
    }

    public function displayCategory()
    {
        return Category::all();
    }

    public function displaySpecificCategory($id)
    {
        return Category::find($id);
    }

}