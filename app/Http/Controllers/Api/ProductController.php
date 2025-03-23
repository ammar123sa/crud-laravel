<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Dotenv\Validator as Dv ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function index() {
        $products = Product::get() ;
        if($products->count() > 0)
        {
            return ProductResource::collection($products);
        }
        else{
            return response()->json(['message' =>'No record' ],200);
        }
    }
    public function store(Request $request){
            $validator= Validator::make($request->all(),
                [
                    'name'=>'required|string|max:255',
                    'description'=>'required',
                    'price' => 'required|numeric',
                ]);
            if($validator->fails())
            {
                return response()->json(['message'=>'All fields are mandetory' ,
                'error'=>$validator->messages()] , 422);
            }
            $data = $request->all() ;
            $data['user_id'] = $request->user()->id ;
            $product = Product::create($data) ;
            return response()->json(['message'=>'Product Created Successfully',
            'data'=>new ProductResource($product)
            ] , 200) ;
    }
    public function show(Product $product){
            return new ProductResource($product) ;

    }
    public function update(Product $product, Request $request)
{
    // check
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|string|max:255',
        'description' => 'sometimes',
        'price' => 'sometimes|numeric',
    ]);

    // fail
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation Error',
            'error' => $validator->messages()
        ], 422);
    }

    // update
    $product->update($request->only(['name', 'description', 'price']));


    return response()->json([
        'message' => 'Product Updated Successfully',
        'data' => new ProductResource($product)
    ], 200);
}
public function destroy(Product $product)
{
    $product->delete() ;
    return response()->json([
        'message' => 'Product Deleted Successfully',200
    ]);
}
}
