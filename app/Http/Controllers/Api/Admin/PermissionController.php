<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function __invoke()
    {
        //get permission based on loged user
        return auth()->guard('api')->user()->getAllPermissions()->pluck('name');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'permission' => 'required|unique:permissions',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permission = Permission::create([
            'permission' => $request->permission
        ]);

        if($permission) {
            //return success with Api Resource
            return new PermissionResource(true, 'Data Permission Berhasil Disimpan!', $permission);
        }

        //return failed with Api Resource
        return new PermissionResource(false, 'Data Permission Gagal Disimpan!', null);

    }

    public function show($id){
        $permission = Permission::whereId($id)->first();

        if($permission) {
            //return success with Api Resource
            return new PermissionResource(true, 'Data Permission Berhasil Disimpan!', $permission);
        }

        //return failed with Api Resource
        return new PermissionResource(false, 'Data Permission Gagal Disimpan!', null);

    }

    public function update(Request $request,Permission $permission){
        $validator = Validator::make($request->all(),[
            'permission' => 'required|unique:permissions,permission,'.$permission->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permission->update([
            'permission' => $request->permission
        ]);

        if($permission) {
            //return success with Api Resource
            return new PermissionResource(true, 'Data Permission Berhasil Disimpan!', $permission);
        }

        //return failed with Api Resource
        return new PermissionResource(false, 'Data Permission Gagal Disimpan!', null);


    }
    
    public function destroy(Permission $permission){
        //revoke the permission from role
        $role = Role::all()->pluck('role');
        $role->revokePermissionTo($permission);

        if($permission->delete()) {
            //return success with Api Resource
            return new PermissionResource(true, 'Data Permission Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new PermissionResource(false, 'Data Permission Gagal Dihapus!', null);

    }
}
