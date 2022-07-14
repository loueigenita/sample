<?php

namespace App\Http\Controllers\Api;

 use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'lname' => 'string|required',
            'fname' => 'string|required',
            'mobile' => 'string|required',
            'password' => 'string|required',
            'email' => 'email|required',
        ]);
        if($validator->fails()) {
            return response()->json([
                'message'=>$validator->errors()
            ]);
        }
        $user = User::create([
            'lname' => $request->lname,
            'fname' => $request->fname,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

            $token = $user->createToken('auth')->plainTextToken;

            return response()->json([
                'status'=>'success',
                'message'=>'User Registered Successfully',
                'user'=> $user,
                'token'=> $token
            ]);
        }
        public function login(Request $request){
            $request->validate([
                'email'=>'email|required',
                'password'=>'string|required'
            ]);

            $login = auth()->attempt($request->only('email','password'));

            if(!$login){
                return response()->json([
                    'status'=>'error',
                    'message'=>'Invalid User Credentials'
                ]);
            }

            $user = auth()->user();

            $token = $user->createToken('auth')->plainTextToken;

            return response()->json([
                'status'=>'success',
                'user'=>$user,
                'token'=>$token,

            ]);
        }
        public function user(){
            return auth()->user();
        }

        public function logout(){
           auth()->user()->tokens()->delete();
            return response()->json([
                'status'=>'success',
                'message'=>'User Log Out Successfully'
            ]);
        }
}
