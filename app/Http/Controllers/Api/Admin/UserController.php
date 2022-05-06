<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //get categories
        $user = User::with('roles')->when(request()->q, function($user) {
            $user = $user->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(2);
        
        //return with Api Resource
        return new UserResource(true, 'List Data User!', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('roles.permissions')->whereId($id)->first();
        
        if($user) {
            //return success with Api Resource
            return new UserResource(true, 'Detail Data User!', $user);
        }

        //return failed with Api Resource
        return new UserResource(false, 'Detail Data User Tidak DItemukan!', null);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required:max:20',
            'password' => 'required|min:6',
            'confirmPassword' => 'min:6|required|same:password',
            'role'  => 'required',
            'email' => 'required|email|unique:users,email',

        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email
        ]);

        $user->assignRole($request->role);

        if($user) {
            //return success with Api Resource
            return new UserResource(true, 'Data User Berhasil Disimpan!', $user);
        }

        //return failed with Api Resource
        return new UserResource(false, 'Data User Gagal Disimpan!', null);      
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user , Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required:max:20',
            'role'  => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6',
            'confirmPassword' => 'nullable|min:6|same:password',

        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //if password and cpassword exist ,update the password also
        if($request->password || $request->confirmPassword){
            $user->update([
                'password' =>Hash::make($request->password),
            ]);
        }

        $user->update([
            'name' => $request->name,
            'email' =>$request->email
        ]);

        $user->assignRole($request->role);

        if($user) {
            //return success with Api Resource
            return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
        }

        //return failed with Api Resource
        return new UserResource(false, 'Data User Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->delete()) {
            //return success with Api Resource
            return new UserResource(true, 'Data User Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new UserResource(false, 'Data User Gagal Dihapus!', null);
    }
}
