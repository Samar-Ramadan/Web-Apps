<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        $files= Files::all();
       // return $files;
        return response()->json([
         'success' => true,
         'name' => $files->name,
         'file ' =>$files->path,
         'msg'=>'get product has been successfully'

        ]); 
    }

    public function store(Request $request)
    {
        
        $validator=Validator::make($request->all(),[
           'name'=>'required|string',
           'path'=>'required|image',

         ]);
         
         if($validator->fails()){
             return response()->json([
                 'success'=>false,
                 'msg'=>'validate error.',$validator->errors()
             ],404);
            }

            
             $file = $request->path->getClientOriginalName();
             $request->file->move(public_path('images/'), $file);
            $id=Auth::id();
            //$user=User::find(Auth::user()->id);
            $product=new product([
            "name"=>$request->name,
            "path"=>$file,
            "user_id"=>1,
          ]);
        
     
        $product->save(); 

    

        }
   
}
