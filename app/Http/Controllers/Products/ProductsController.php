<?php

// // include composer autoload
// require 'vendor/autoload.php';



namespace App\Http\Controllers\Products;
use Validator,Redirect,Response,File;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;

class ProductsController extends Controller
{
    public function addProduct (Request $request){

        $var = json_decode($request->product);
    

    
        // return response()->json(number_format($var->buy_price_products, 2, '.', ''));


       $validator = Validator::make($request->all(), 
              [ 
              // 'user_id' => 'required',
              'file' => 'required|max:2048',
             ]);   
 
    if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);                        
         }  
 
  
        if ($files = $request->file('file')) {

   
            // return response()->json($request->file('file'));
 

            $image_name = Carbon::now();
            $image_name = $valor = str_replace("-", "", $image_name);
            $image_name = $valor = str_replace(" ", "", $image_name);
            $image_name = $valor = str_replace(":", "", $image_name);

   
            $path = image::make($request->file('file'))->save('storage/products/'.$image_name.'.jpg');

            $path = 'storage/products/'.$image_name.'.jpg';
            
         }else{
             $path  = 'storage/products/default.jpg';
         }   
            //store your file into database
            $document = new Product();
            $document->patch = $path;
            $document->description_products = $var->description_products;
            $document->unity_products = $var->unity_products;
            $document->buy_price_products = number_format($var->buy_price_products, 2, '.', '');
            $document->sell_price_products = number_format($var->sell_price_products, 2, '.', '');
            $document->earning_products = number_format($var->earning_products, 2, '.', '');          
            $document->save();
               return response()->json($document);
            // return response()->json([
            //     "success" => true,
            //     "message" => "File successfully uploaded"
            //     // "file" => $path
            // ]);
       
    }

    public function GetProducts (Request $request){

        $all = Product::orderBy('id','DESC')
        ->paginate(15);

        return response()->json($all);

    }

    public function GetProductById (Request $request){

        $id = $request->id;

        $return = Product::where('id',$id)->first();

        return response()->json($return);

    }

    public function deletProduct (Request $request){


        if ($request['patch'] != 'storage/products/default.jpg'){
            unlink($request['patch']);
        }        
        $id = $request['id'];
        $Product = Product::findOrFail($id);
        $Product->delete();
    }


    public function editProduct (Request $request){
// return response()->json($request);
        $var = json_decode($request->product);
    

    
        // return response()->json(number_format($var->buy_price_products, 2, '.', ''));

 
  
        if ($files = $request->file('file')) {

        $validator = Validator::make($request->all(), 
                [ 
                // 'user_id' => 'required',
                'file' => 'required|max:2048',
                ]);  
            // return response()->json($request->file('file'));
   if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);                        
         }

            $image_name = Carbon::now();
            $image_name = $valor = str_replace("-", "", $image_name);
            $image_name = $valor = str_replace(" ", "", $image_name);
            $image_name = $valor = str_replace(":", "", $image_name);

   
            $path = image::make($request->file('file'))->save('storage/products/'.$image_name.'.jpg');

            $path = 'storage/products/'.$image_name.'.jpg';
            
         }  
            //store your file into database
            $document = Product::find($var->id);
            if(isset($path)){
                $document->patch = $path;
            }
            $document->description_products = $var->description_products;
            $document->unity_products = $var->unity_products;
            $document->buy_price_products = number_format($var->buy_price_products, 2, '.', '');
            $document->sell_price_products = number_format($var->sell_price_products, 2, '.', '');
            $document->earning_products = number_format($var->earning_products, 2, '.', '');          
            $document->save();
               return response()->json($document);
            // return response()->json([
            //     "success" => true,
            //     "message" => "File successfully uploaded"
            //     // "file" => $path
            // ]);
       
    }
}