<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
Use Validator;
use App\product;

class ProductController extends Controller
{

    public function index()
    {
        $products= product::all();
        return $products;
        return response()->json([
         'success' => true,
         'products_name' => $products->name,
         'product_image ' =>$product->file_path,
         'msg'=>'get product has been successfully'

        ]); 
    }

    public function store(Request $request)
    {
        
        $validator=Validator::make($request->all(),[
           'name'=>'required|string',
           'file_path'=>'required|image',
            'date_expir'=>'required',
            'categorize' =>'required|string',
            'communicate' =>'required',
            'amount'=>'required|integer',
            'price'=>'required',
          //  'user_id'=>'required'

         ]);
         
         if($validator->fails()){
             return response()->json([
                 'success'=>false,
                 'msg'=>'validate error.',$validator->errors()
             ],404);
            }

            
             $imageName = $request->file_path->getClientOriginalName();
             $request->file_path->move(public_path('images/'), $imageName);
            $id=Auth::id();
            //$user=User::find(Auth::user()->id);
            $product=new product([
            "name"=>$request->name,
            "file_path"=>$imageName,
            "date_expir"=>$request->date_expir,
            "categorize"=>$request->categorize,
            "communicate"=>$request->communicate,
            "amount"=>$request->amount,
            "price"=>$request->price,
            "user_id"=>$id,
          ]);
        
     
        $product->save(); 
        foreach ($request->list_discounts as $discount){
            $product->discounts()->create([
                
                'discount_value' => $discount['discount_value'],
                'date' => $discount['date'],
               'product_id'=> $request->user_id
           ]);

     }
             return response()->json([
                'success'=>true,
                'msg'=>'product create successfuly.',
             ],200);

           /*  $product->discounts()->create([
                'date' => '08-02-2022',
                'discount_value' => 1000
            ]); */
          


        }
         public function update(Request $request,$id)
         {    
             $product = Product::findOrFail($id);
             $validator=Validator::make($request->all(),[
                'name'=>'required|string',
               // 'file_path'=>'required|image',
                // 'date_expir'=>'required',
                // 'categorize' =>'required|string',
                // 'communicate' =>'required',
                 'amount'=>'required|integer',
                // 'price'=>'required',
                // 'user_id'=>'required'
              ]);
              
              /*if($validator->fails()){
                  return response()->json([
                      'success'=>false,
                      'msg'=>'validate error.',$validator->errors()
                  ],404);
                 }  
                  $imageName = $request->file_path->getClientOriginalName();
                  $request->file_path->move(public_path('images/'), $imageName);
     
                 
                $product->name=$request->name;
                    $product->file_path =$imageName;
                    $product->date_expir=$request->date_expir;
                    $product->categorize=$request->categorize;
                    $product->communicate=$request->communicate;
                    $product->amount=$request->amount;
                    $product->price=$request->price;
                    $product->user_id=$request->user_id;
                         
                    $id=Auth::id();
                    if($id==$request->user_id){
             $product->save(); 
                    }
                  return response()->json([
                     'success'=>true,
                     'msg'=>'product create successfuly.',
                  ],200);*/

                  $product->name=$request->input('name');
                  $product->amount=$request->input('amount');
                  $product->save();
                  return response()->json([
                    'success'=>true,
                    'msg'=>'product create successfuly.',
                 ],200);

         }

         public function delete($id)
         {
            //$find_id=Product::findOrFail($id);
            
             
            //$id=Auth::id();
            
           
             $product = Product::findOrFail($id)->delete();
            
             return response()->json([

                 'success'=>true,
                 'msg'=>'product delete successfuly.',
                 'id'=>$user,
             ],200);
         }


         public function search(Request $request)
         {
             
            $validator=Validator::make($request->all(),[
                'search'=>'required',
              ]);
             if($validator->fails()){
                return response()->json([
                    'success'=>false,
                    'msg'=>'validate error.',$validator->errors()
                ],404);
               } 
               
             $products = Product::where('name','like','%'.$request->search .'%')
             ->orWhere('date_expir','like','%'.$request->search .'%')
             ->orWhere('categorize','like','%'.$request->search .'%')
             ->get();
             
             return response()->json([
                'success' => true,
                'products' => $products,
                'msg'=>'search'
       
               ]);
         }

         public function show($id){

           
             $product=Product::find($id);
             $discount=$product->discounts()->get();
             $maxdiscount=null;
             foreach ($discount as $discount){
             if(Carbon::parse($discount['date'])<=now()){
                $maxdiscount=$discount;
            }
         }


         if(!is_null($maxdiscount)){
             $discount_value=($product->price*$maxdiscount['discount_value'])/100;
             $product['current_price']=$product->price - $product_value;
         }
             return response()->json([
              'product'=>$product

             ]);
         }

        
    }

