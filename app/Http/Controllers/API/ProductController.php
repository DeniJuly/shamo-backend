<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $description = $request->input('description');
        $categories = $request->input('categories');
        $tags = $request->input('tags');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if($id){
            $product = Product::with(['category', 'galleries'])->find($id);
            if($product){
                return ResponseFormatter::success($product, 'Data produk berhasil diambil');
            } else {
                return ResponseFormatter::success(null, 'Data produk tidak ditemukan');
            }
        }

        $products =  Product::with(['category', 'galleries']);
        if($name){
            $products->where('name', 'like', '%'.$name.'%');
        }
        if($description){
            $products->where('description', 'like', '%'.$description.'%');
        }
        if($categories){
            $products->where('categories_id', $categories);
        }
        if($tags){
            $products->where('tags', 'like', '%'.$tags.'%');
        }
        if($price_from){
            $products->where('price', '>=', $price_from);
        }
        if($price_to){
            $products->where('price', '<=', $price_to);
        }

        return ResponseFormatter::success($products->paginate($limit), 'Data produk berhasil diambil');
    }
}
