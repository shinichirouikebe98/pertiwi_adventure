<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ImageResource;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function index()
    {
        //get categories
        $images = Image::with('user')->when(request()->q, function($images) {
            $images = $images->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(25);
        
        //return with Api Resource
        return new ImageResource(true, 'List Data Gambar', $images);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'    => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name'  => 'required',
            'category' => 'required',
            'keterangan'   => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/image', $image->hashName());

        //create category
        $image = Image::create([
            //'nama field'
            'user_id' => auth()->guard('api')->user()->id,
            'img'=> $image->hashName(),
            'name' => $request->name,
            'keterangan' => $request->keterangan,
            'category' => $request->category,
            
        ]);

        if($image) {
            //return success with Api Resource
            return new ImageResource(true, 'Data Image Berhasil Disimpan!', $image);
        }

        //return failed with Api Resource
        return new ImageResource(false, 'Data Image Gagal Disimpan!', null);
    }
    public function show($id)
    {
        $image = Image::whereId($id)->first();
        
        if($image) {
            //return success with Api Resource
            return new ImageResource(true, 'Detail Data Image!', $image);
        }

        //return failed with Api Resource
        return new ImageResource(false, 'Detail Data Image Tidak DItemukan!', null);
    }
    public function update(Request $request, Image $image)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'category' => 'required',
            'keterangan'   => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/image/'.basename($image->img));
        
            //upload new image
            $images = $request->file('image');
            $images->storeAs('public/image', $images->hashName());

            $image->update([
                'img'=> $images->hashName(),
                'name' => $request->name,
                'keterangan' => $request->keterangan,
                'category' => $request->category,
                'user_id' => auth()->guard('api')->user()->id,
            ]);

        }
         //update data without image  
        $image->update([
            'name' => $request->name,
            'keterangan' => $request->keterangan,
            'category' => $request->category,
            'user_id' => auth()->guard('api')->user()->id,
        ]);


        if($image) {
            //return success with Api Resource
            return new ImageResource(true, 'Data Image Berhasil Diupdate!', $image);
        }

        //return failed with Api Resource
        return new ImageResource(false, 'Data Image Gagal Diupdate!', null);
    }

    public function destroy(Image $image)
    {
        //remove image
        Storage::disk('local')->delete('public/image/'.basename($image->img));  

        if($image->delete()) {
            //return success with Api Resource
            return new ImageResource(true, 'Data Image Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new ImageResource(false, 'Data Image Gagal Dihapus!', null);
    }
}
