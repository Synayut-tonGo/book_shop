<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\Cart;
use App\Models\User;
use App\Supports\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
        $user = User::with('roles')->paginate(15);
        return ApiResponse::collection($user);
        }catch (\Exception $e) {
            return ApiResponse::error('Cannot get user data', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try{
        $data = $request->validated();

        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('users/profiles', 'public');
        }

        $user = User::create(
            ['first_name' => $data['first_name'] ,
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'gender' => $data['phone'],
            'image' => $data['image'] ?? null,]
        );

        if($request->role_id) {
            $user->roles()->attach($request->role_id ,['assigned_by' => auth('api')->id()]);
        }

        return ApiResponse::success($user->load('roles'), 'User created successfully');           
        }catch(\Exception $e){
            return ApiResponse::error('Cannot created user', 500 , $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try{
            $datauser  = $user->load('roles.permissions');
            return ApiResponse::single($datauser,'Get user successfully');
        }catch(\Exception $e){
            return ApiResponse::error('Error get user' , 500 , $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try{
            $data = $request->validated();

            if($request->hasFile('image')){
                if($user->image && Storage::disk('public')->exists($user->image)){
                    return Storage::disk('public')->delete($user->user);
                }
                $data['image']= $request->file('image')->store('users/profiles','public');
            }

            if ($request->filled('password')){
                $data['password'] = Hash::make($request->password);
            }

            if ($request->filled('role_id')){
                if($user->role_id){
                    $user->roles()->sync([$request->role_id, ['assigned_by' => auth('api')->id()]]);
                }
            }

            $user->update($data);
            

            return ApiResponse::success($user->load('roles.permissions'));

        }catch(\Exception $e){
            return ApiResponse::error('Cannot update user' , 500 , $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try{
            $data = $user->delete();
            return ApiResponse::success($data , 'User deleted successfully');
        }catch(\Exception $e){
            return ApiResponse::error('Cannot delete user', 500 , $e->getMessage());
        }
    }
    public function destroyMulti(Request $request)
    {
        try{
            $request->validate([
                'ids' => 'required|array|exists:users,user_id',
            ]);
            $ids = $request->input('ids');
            $data = User::whereIn('id', $ids)->get();
            User::whereIn('id', $ids)->delete();
            return ApiResponse::success($data , 'User deleted successfully');
        }catch(\Exception $e){
            return ApiResponse::error('Cannot delete user', 500 , $e->getMessage());
        }
    }
    public function destroyAll()
    {
        try{
            $data = User::all();
            User::query()->delete();
            return ApiResponse::success($data , 'User deleted successfully');
        }catch(\Exception $e){
            return ApiResponse::error('Cannot delete user', 500 , $e->getMessage());
        }
    }        
}
