<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    public function __invoke(User $user)
    {
        return auth()->guard('api')->user()->getRoleNames();
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'role' => 'required|unique:roles,role',
            'permission' => 'required|min:1'
        ]);

        $role = Role::create([
            'role' => $request->role
        ]);
        $role->syncPermission($request->permission);

        if($role) {
            //return success with Api Resource
            return new RoleResource(true, 'Data Role Berhasil Disimpan!', $role);
        }

        //return failed with Api Resource
        return new RoleResource(false, 'Data Role Gagal Disimpan!', null);

    }
    public function update(Request $request,Role $role){
        $validator = Validator::make($request->all(),[
            'role' => 'required|unique:roles,role,'.$role->id,
            'permission' => 'required|min:1'
        ]);

        $role->update([
            'role' => $request->role
        ]);
        $role->syncPermission($request->permission);

        if($role) {
            //return success with Api Resource
            return new RoleResource(true, 'Data Role Berhasil Disimpan!', $role);
        }

        //return failed with Api Resource
        return new RoleResource(false, 'Data Role Gagal Disimpan!', null);

    }

    public function destroy(){
        $user = User::all()->pluck('name');
        
    }

    
}
