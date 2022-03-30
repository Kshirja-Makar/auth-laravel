<?php

namespace App\Http\Controllers;
use Hash;
use Illuminate\Http\Request;
use App\Models\User;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class UserController extends Controller
{
    //
    public function register(Request $request){

        $user=User::where('email',$request['email'])->first();
        if($user){
            $response['status']=0;
        $response['message']='User Already Exists';
        $response['code']=409;
        }
        else{
        $user=User::create([
            'firstName'=> $request->firstName,
            'lastName'=> $request->lastName,
            'email'=>$request->email,
            'phone'=> $request->phone,
            'password'=>bcrypt($request->password),
            'referedBy'=> $request->referedBy,
            'isAutherised'=> $request->isAutherised,

        ]);
        $response['status']=1;
        $response['message']='User Register Successfully';
        $response['code']=200;
       
    }
    return response()->json($response);
    }

    public function login(Request $request){
        $credentials=$request->only('email','password');
        try{
            if(!JWTAuth::attempt($credentials)){
                $response['data']=null;
                $response['status']=0;
                $response['code']=501;
                $response['message']='email or password incorrect';
                return response()->json($response);
            }}
            catch(JWTException $e){
                $response['data']=null;
                $response['code']=500;
                $response['message']='could not create token';
                return response()->json($response);
             }
           $user=auth()->user();
           $data['token']=auth()->claims([
               'user_id'=>$user->id,
               'email'=>$user->email
           ])->attempt($credentials) ;

           $response['data']=$data;
           $response['status']=1;
           $response['code']=200;
           $response['message']='login successfully';
           return response()->json($response);
      
        }
        


    }
