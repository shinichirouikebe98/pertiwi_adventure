<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function __invoke()
    {
        //get permission based on loged user
        return auth()->guard('api')->user()->getAllPermissions()->pluck('name');
    }
    public function index()
    {
        //get categories
        $permissions = Permission::when(request()->q, function($permissions) {
            $permissions = $permissions->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(20);
        
        //return with Api Resource
        return new PermissionResource(true, 'List Data Permission', $permissions);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'api'
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

    
    public function destroy(Permission $permission){

        if($permission->delete()) {
            //return success with Api Resource
            return new PermissionResource(true, 'Data Permission Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new PermissionResource(false, 'Data Permission Gagal Dihapus!', null);

    }

    public function getAllPermissionsName(){
        $permissions = Permission::select('name')->get();

        return $permissions;
    }
}
