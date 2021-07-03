<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait;

    public function login(Request $request){
        try {
            // Validation
            $rules = [
                'email' => 'required',
                'password' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // Login
            $credentials = $request->only(['email', 'password']);
            $token = Auth::guard('user-api')->attempt($credentials);
            if (!$token) {
                return $this->returnError('E001', 'بيانات الدخول غير صحيحة.');
            }

            // Return Data With Api Token
            $userData = Auth::guard('user-api')->user();
            $userData->api_token = $token;
            return $this->returnData('user', $userData);
        } catch (\Exception $e) {
            return $this->returnError($e->getCode(), $e->getMessage());
        }
    }

    public function logout(Request $request){
        $token = $request->header('auth-token');
        if ($token) {
            // Set Token To Invalidate Token - Logout
            try {
                JWTAuth::setToken($token)->invalidate();
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return $this->returnError('', 'Something Went Wrong.');
            }
            return $this->returnSuccessMessage('Logged Out Successfully.');
        } else {
            return $this->returnError('E002', 'Something Went Wrong.');
        }
    }
}
