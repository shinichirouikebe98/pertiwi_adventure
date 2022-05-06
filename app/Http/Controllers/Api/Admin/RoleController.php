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
    public function index()
    {
       
        //get categories
        $roles = Role::with('permissions')->when(request()->q, function($roles) {
            $roles = $roles->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(15);

        return $roles;
        //return with Api Resource
        // return new RoleResource(true, 'List Data Role', $roles);
    }
    public function store(Request $request){
       
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:roles,name',
            'permission' => 'required|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'api'
                
        ]);
        
        $role->syncPermissions(explode(',',$request->permission));

        if($role) {
            //return success 
            return 'berhasil di simpan';
        }

        //return failed 
        return 'gagal di simpan';

    }

    public function update(Request $request,Role $role){
        $validator = Validator::make($request->all(),[
            'name' => 'unique:roles,name,'.$role->id,
            'permission' => 'required|min:1'
        ]);

        $role->update([
            'name' => $request->name
        ]);
        $role->syncPermissions(explode(',',$request->permission));

        if($role) {
            //return success 
            return 'Data Berhasil di update';
        }

        //return failed 
        return 'Data Gagal di update';

    }
    public function show($id)
    {
        $role = Role::with('permissions')->whereId($id)->first();
        
        if($role) {
            //return success 
            return $role;
        }

        //return failed 
        return $role;
    }
    public function getRoleNames(){
        $roles = Role::select('name')->get();
        return new RoleResource(true, 'List Data Role!', $roles);
    }

    public function destroy(Role $role){
        
        if($role->delete()) {
            //return success with Api Resource
            return 'berhasil di hapus';
        }

        //return failed with Api Resource
        return 'berhasil di hapus';
    }

    
}
