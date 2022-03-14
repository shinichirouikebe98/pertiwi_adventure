<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index()
    {
        //get video
        $videos = Video::with('user')->when(request()->q, function($videos) {
            $videos = $videos->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        
        //return with Api Resource
        return new VideoResource(true, 'List Data Video', $videos);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required|unique:videos',
            'link'   => 'required|url',
            'description' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create video
        $video = Video::create([
            'title' => $request->title,
            'link' => $request->link,
            'description' => $request->description,
            'user_id' => auth()->guard('api')->user()->id,
            'slug' => Str::slug($request->title, '-'),
        ]);

        if($video) {
            //return success with Api Resource
            return new VideoResource(true, 'Data Video Berhasil Disimpan!', $video);
        }

        //return failed with Api Resource
        return new VideoResource(false, 'Data Video Gagal Disimpan!', null);
    }
    public function show($id)
    {
        $video = Video::whereId($id)->first();
        
        if($video) {
            //return success with Api Resource
            return new VideoResource(true, 'Detail Data Video!', $video);
        }

        //return failed with Api Resource
        return new VideoResource(false, 'Detail Data Video Tidak DItemukan!', null);
    }
    public function update(Request $request, Video $video)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required',
            'link'   => 'required|url',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

         $video->update([
            'title' => $request->title,
            'link' => $request->link,
            'description' => $request->description,
            'slug' => Str::slug($request->title, '-'),
        ]);

        


        if($video) {
            //return success with Api Resource
            return new VideoResource(true, 'Data Video Berhasil Diupdate!', $video);
        }

        //return failed with Api Resource
        return new VideoResource(false, 'Data Video Gagal Diupdate!', null);
    }

    public function destroy(Video $video)
    {
        if($video->delete()) {
            //return success with Api Resource
            return new VideoResource(true, 'Data Video Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new VideoResource(false, 'Data Video Gagal Dihapus!', null);
    }
}
