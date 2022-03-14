<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Berita;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\BeritaResource;
use Illuminate\Support\Facades\Validator;

class BeritaController extends Controller
{
    public function index()
    {
        //get categories
        $beritas = Berita::with('user')->when(request()->q, function($beritas) {
            $beritas = $beritas->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(2);
        
        //return with Api Resource
        return new BeritaResource(true, 'List Data Berita', $beritas);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'img'    => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'title'  => 'required|unique:beritas',
            'news'   => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('img');
        $image->storeAs('public/berita', $image->hashName());

        //create category
        $berita = Berita::create([
            'img'=> $image->hashName(),
            'title' => $request->title,
            'news' => $request->news,
            'user_id' => auth()->guard('api')->user()->id,
            'slug' => Str::slug($request->title, '-'),
        ]);

        if($berita) {
            //return success with Api Resource
            return new BeritaResource(true, 'Data Berita Berhasil Disimpan!', $berita);
        }

        //return failed with Api Resource
        return new BeritaResource(false, 'Data Berita Gagal Disimpan!', null);
    }
    public function show($id)
    {
        $berita = Berita::whereId($id)->first();
        
        if($berita) {
            //return success with Api Resource
            return new BeritaResource(true, 'Detail Data Berita!', $berita);
        }

        //return failed with Api Resource
        return new BeritaResource(false, 'Detail Data Berita Tidak DItemukan!', null);
    }
    
    public function update(Request $request, Berita $berita)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required|unique:beritas,title,'.$berita->id,
            'news'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('img')) {

            //remove old image
            Storage::disk('local')->delete('public/berita/'.basename($berita->img));
        
            //upload new image
            $image = $request->file('img');
            $image->storeAs('public/berita', $image->hashName());

            $berita->update([
                'title'=> $request->title,
                'img'=> $image->hashName(),
                'news' => $request->news,
                'slug' => Str::slug($request->title, '-'),
                'user_id'=> auth()->guard('api')->user()->id
            ]);

        }
         //update data without image  
        $berita->update([
            'title'=> $request->title,
            'news' => $request->news,
            'slug' => Str::slug($request->title, '-'),
            'user_id' => auth()->guard('api')->user()->id,
        ]);


        if($berita) {
            //return success with Api Resource
            return new BeritaResource(true, 'Data Berita Berhasil Diupdate!', $berita);
        }

        //return failed with Api Resource
        return new BeritaResource(false, 'Data Berita Gagal Diupdate!', null);
    }

    public function destroy(Berita $berita)
    {
        //remove image
        Storage::disk('local')->delete('public/berita/'.basename($berita->img));  

        if($berita->delete()) {
            //return success with Api Resource
            return new BeritaResource(true, 'Data Berita Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new BeritaResource(false, 'Data Berita Gagal Dihapus!', null);
    }
}
