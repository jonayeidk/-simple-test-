<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Helpers\Helper;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use App\Events\WebsocketDemoEvent;

class AuthController extends Controller
{
    public $token = true;
  
    public function register(Request $request)
    {
        try{
            
            $validator = Validator::make($request->all(), 
                      [ 
                      'name' => 'required',
                      'email' => 'required|email',
                      'password' => 'required',  
                      'c_password' => 'required|same:password', 
                     ]);  
 
            if ($validator->fails()) {
                return Helper::responseError($validator->errors());
            } 
    
    
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
    
            if ($this->token) {
                return $this->login($request);
            }
    
            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);

         } catch (\Exexption $e) {
             return Helper::responseError($e->getMessage());
        }
    }
  
    public function login(Request $request)
    {
        try{

            $input = $request->only('email', 'password');
            $jwt_token = null;
    
            if (!$jwt_token = JWTAuth::attempt($input)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Email or Password',
                ], Response::HTTP_UNAUTHORIZED);
            }
    
            return response()->json([
                'success' => true,
                'token' => $jwt_token,
            ]);

         } catch (\Exexption $e) {
             return Helper::responseError($e->getMessage());
        }
    }
  
    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return Helper::responseError($validator->errors());
        }
  
        try {
            JWTAuth::invalidate($request->token);
  
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
  
    public function getUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);

            if ($validator->fails()) {
                return Helper::responseError($validator->errors());
            }

            $user = JWTAuth::authenticate($request->token);
    
            return response()->json(['user' => $user]);

         } catch (\Exexption $e) {
             return Helper::responseError($e->getMessage());
        }
    }


     public function user_list(Request $request){
        try {

            $data = User::select('name','email')->get();
            return Helper::responseSuccess($data);

        } catch (\Exexption $e) {
             return Helper::responseError($e->getMessage());
        }
    }

    public function send_user_list(Request $request){
        try {

            $data = User::select('name','email')->get();
            broadcast(new WebsocketDemoEvent($data));
            return Helper::responseSuccess($data);

        } catch (\Exexption $e) {
             return Helper::responseError($e->getMessage());
        }
    }
}
