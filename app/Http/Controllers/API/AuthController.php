<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
Use Validator;
//use Illuminate\Validation\Validator;
class AuthController extends Controller
{
    public function register(Request $request){
        //Samar Ramadan
$d= array([
    'name' => 'required|string',
    'email'=>'rquired|string|email|unique:users',
    'password'=>'required|confirmed|min:6',

]);
$this->validate($request,$d);
     

$input =$request->all();
$input['password']=bcrypt($input['password']);
$user=User::create($input);
return response()->json([
                            "message"=>"successfully created user",
                             "user"=>$user,
                        ],201);
  
      /*  $user=new User([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>bcrypt($request->password)
        ]);
        $user->save(); */
    }


public function login(Request $request){
        $d= array([
            'email'=>'rquired|string|email',
        'password'=>'required|string',
        'remember_me'=>'boolean'
        
        ]);
     $this->Validate($request,$d);
     $credentials =$request->only("email","password");
     if($token=$this->guard()->attempt($credentials)){
     return $this->respondWithToken($token);

     }
     return response()->json([
       "error"=>"login_error"

     ],401);
    }

    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }

public function user(Request $request){
    $user=User::find(Auth::user()->id);
    return response()->json(['data'=>$user]);
}

public function logout(){
    $this->guard()->logout();
    return response()->json([
        "status" => "success",
        "msg"=>"Logged out successfully."
    ],200);
}

    private function guard(){
     return Auth::guard();
    }

    protected function respondWithToken($token){
       return response()->json([
        'access_token'=>$token,
        'token_type'=>'bearer'

       ]);
    }
}
