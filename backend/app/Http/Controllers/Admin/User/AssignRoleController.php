<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\AssignRoleRequest;
use App\Models\User;
use App\Services\RoleServices;
use App\Supports\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class AssignRoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleServices $roleService)
    {
        return $this->roleService = $roleService;
    }

    public function assignRole (AssignRoleRequest $request , User $user){
           
            try{

                $this->roleService->assignRoleToUser($user ,$request->role_id , auth('api')->id());

                return ApiResponse::success($user->fresh()->load('roles') , 'Assigned Role Successfully');

            }catch(\Exception $e){
                return ApiResponse::error($e->getMessage(), 500);
            }

    } 
    
}
