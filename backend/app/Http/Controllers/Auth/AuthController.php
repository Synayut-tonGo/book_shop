<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Supports\ApiRepsonse;
use App\Supports\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register (RegisterRequest $request){
        
        try{
            $data = $request->validated();
            if ($request->hasFile('image')){
                $data['image'] = $request->file('image')->store('users/profiles', 'public');
            }
            $userRegister = User::create([
                'first_name'=> $data['first_name'],
                'last_name'=> $data['last_name'],
                'email'=> $data['email'],
                'password'=> Hash::make($data['password']),
                'phone'=> $data['phone'],
                'gender'=> $data['gender'],
                'dob'=> $data['dob'],
                'image'=> $data['image'] ?? null,
            ]);
            $roleCustomer = Role::where('slug', 'customer')->first();
            if($roleCustomer) {
                $userRegister->roles()->attach($roleCustomer->role_id, ['assigned_by' => null]);
            }
            $credential = $request->only('email', 'password');
        if (!$token = Auth::guard('api')->attempt($credential)) {
            return ApiResponse::error('Invalid credentials', 401, null);
        }

            return ApiResponse::success(['user'  => $userRegister->load('roles'),
            'token' => $token], 'User registeration successfully');

        }catch(\Exception $e){
            return ApiResponse::error('Cannot register user',500,$e->getMessage());
        }

    }

    public function login (LoginRequest $request) {
        
        $credential = $request->only('email', 'password');

        $token = JWTAuth::attempt($credential);

        if(!$token){
            return ApiResponse::error('Invalid Credential',401);
        }
        
        $user = JWTAuth::user()->load('roles');

        return ApiResponse::success(['user' => $user , 'token' => $token] , 'User login successfully');
    
    }

    public function fetchUser() {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if(!$user){
                return ApiResponse::error('User not found' , 404 );
            }

            return ApiResponse::success(['user' => $user->load('roles')]);
        }catch(TokenExpiredException $e){
            return ApiResponse::error('Token Expired' , 401);
        }catch(TokenInvalidException $e){
            return ApiResponse::error('Token Invalid' , 401);
        }catch(\Exception $e){
            return ApiResponse::error('Token not provide' , 401);
        }
    } 

    public function logout (Request $request){
        try{
            JWTAuth::invalidate(JWTAuth::getTOken());
            return ApiResponse::success(null, 'User logged out successfully');
        }catch(\Exception $e){
            return ApiResponse::error('Failed to logout user', 500, $e->getMessage());
        }
    }
}
