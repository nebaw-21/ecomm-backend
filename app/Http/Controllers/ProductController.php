<?php

namespace App\Http\Controllers;

use App\Models\product_color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\color;
use Illuminate\Http\UploadedFile;

class ProductController extends Controller
{
    //add product
    public function addProducts(Request $request)
    {
        $formFormat = $request->input('formFormat');
        $colors = $request->input('color');
        $sizes = $request->input('sizes');
        $images = $request->file('images');

        if ($formFormat == 'form1') {
            // Validate the form data
            $validator = Validator::make($request->all(), [
                'productName' => 'required',
                'price' => 'required',
                'description' => 'required',
                'selectedCategoryOption' => 'required',
                'color.*' => 'required',
                'sizes.*' => 'required',
                'is_recommended' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product = new Product([
                'name' => $request->input('productName'),
                'price' => $request->input('price'),
                'categoryId' => $request->input('selectedCategoryOption'),
                'description' => $request->input('description'),
                'is_recommended' => $request->input('is_recommended'),
            ]);

            if (!$product->save()) {
                return response()->json(['error' => 'Failed to save the product. Please try again.'], 500);
            }

            // Save colors
            $colors = $request->input('color');
            foreach ($colors as $colorChoice) {
                $color = $product->colors()->create([
                    'colorId' => $colorChoice,
                ]);

                // Save sizes
                $sizes = $request->input('sizes.' . $colorChoice, []);
                foreach ($sizes as $size) {
                    $color->sizes()->create([
                        'size' => $size,
                    ]);
                }

                // Save images
                $images = $request->file('images.' . $colorChoice, []);
                foreach ($images as $image) {
                    if ($image instanceof UploadedFile) {
                        $imagePath = $image->store('productImages');
                        $color->images()->create([
                            'image' => $imagePath,
                        ]);
                    } else {
                        return response()->json(['error' => 'Invalid image data.'], 422);
                    }
                }
            }

            return response()->json(['message' => 'Product added successfully'], 200);
        } elseif ($formFormat == 'form2') {
            $validator = Validator::make($request->all(), [
                'productName' => 'required',
                'price' => 'required',
                'description' => 'required',
                'selectedCategoryOption' => 'required',
                'sizeInput' => 'required',
                'imageInput' => 'required',
                'is_recommended'=>'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $product = new Product([
                'name' => $request->input('productName'),
                'price' => $request->input('price'),
                'categoryId' => $request->input('selectedCategoryOption'),
                'description' => $request->input('description'),
                'is_recommended' => $request->input('is_recommended'),
            ]);

            if (!$product->save()) {
                return response()->json(['error' => 'Failed to save the product. Please try again.'], 500);
            }

            // Save sizes
            $sizes = $request->input('sizeInput');
            if (!is_array($sizes)) {
                return response()->json(['error' => $sizes], 422);
            }

            foreach ($sizes as $size) {
                $product->sizes()->create([
                    'size' => $size,
                ]);
            }

            // Save images
            $images = $request->file('imageInput');
            foreach ($images as $image) {
                if ($image instanceof UploadedFile) {
                    $imagePath = $image->store('productImages');
                    $product->images()->create([
                        'image' => $imagePath,
                    ]);
                } else {
                    return response()->json(['error' => 'Invalid image data.'], 422);
                }
            }

            return response()->json(['message' => 'Product added successfully'], 200);
        } else {
            return response()->json(['message' => 'Invalid form format!!'], 422);
        }
    }


    public function displayProduct()
    {
        $products = Product::all();
    
        $result = [];
        foreach ($products as $product) {
             $product->images;
             $product->sizes;
            
            $colors = $product->colors;
            $colorData = [];
            if(isset($colors)){
                foreach ($colors as $color) {
                     $color->images->pluck('image')->toArray();
                     $color->sizes->pluck('size')->toArray();
        
                    $colorData[] = [
                        'colorName' => color::find($color->colorId)->color,
                
                    ];
                }

            }
    
            $result[] = [
                'product' => $product,
                'colorData' => $colorData,

            ];
        }
    
        return response()->json($result);
    }

    public function displaySpecificProduct($id)
    {
        $productId = $id;
        $product = Product::find($productId);
    
        if ($product) {
            $product->images;
            $product->sizes;
    
            $colors = $product->colors;
            $colorData = [];
            if (isset($colors)) {
                foreach ($colors as $color) {
                    $color->images->pluck('image')->toArray();
                    $color->sizes->pluck('size')->toArray();
    
                    $colorData[] = [
                        'colorName' => Color::find($color->colorId)->color,
                        'colorId' => $color->colorId,
                    ];
                }
            }
    
            $result = [
                'product' => $product,
                'colorData' => $colorData,
            ];
    
            return response()->json($result);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

public function displaySpecificCategoryProducts($id){
    $products = Product::where('categoryId', $id)->get();
    
    $result = [];
    foreach ($products as $product) {
         $product->images;
         $product->sizes;
         $product->category->category;
        
        $colors = $product->colors;
        $colorData = [];
        if(isset($colors)){
            foreach ($colors as $color) {
                 $color->images->pluck('image')->toArray();
                 $color->sizes->pluck('size')->toArray();
    
                $colorData[] = [
                    'colorName' => color::find($color->colorId)->color,
            
                ];
             
            }

        }

        $result[] = [
            'product' => $product,
            'colorData' => $colorData,
       

        ];
    }

    return response()->json($result);
}


}


