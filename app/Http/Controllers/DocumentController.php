<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use App\Models\Document;
use App\Models\User;
use App\Http\Controllers\Controller;


class DocumentController extends Controller
{
 
    public function store(Request $request)
    {
 
        // return response()->json($request->file('file'));

       $validator = Validator::make($request->all(), 
              [ 
              // 'user_id' => 'required',
              'file' => 'required|max:2048',
             ]);   
 
    if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);                        
         }  
 
  
        if ($files = $request->file('file')) {
             
            //store file into document folder
            $file = $request->file->store('public/documents');
            $path = $request->file->store('/documents');
            //store your file into database
            $document = new Document();
            $document->patch = $path;
            $document->user_id = $request->user_id;
            $document->save();
              
            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $path
            ]);
        }
    }

    public function getImages (){
        $get = Document::get();

        return response()->json($get);
    }
}