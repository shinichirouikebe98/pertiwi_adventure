<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Http\Resources\ImageResource;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::latest()->get();
        //return with Api Resource
        return new ImageResource(true, 'List Data Gambar', $images);
    }

    public function gallery_paintball(){
        $images = Image::select('img','name')->where('category','=','Paintball')->get();
        return new ImageResource(true, 'List Gallery Paintball', $images);
    }
    public function gallery_atv(){
        $images = Image::select('img','name')->where('category','=','Atv')->get();
        
        return new ImageResource(true, 'List Gallery Atv', $images);
    }
    public function gallery_rafting(){
        $images = Image::select('img','name')->where('category','=','Rafting')->get();
        return new ImageResource(true, 'List Gallery Rafting', $images);
    }
    public function gallery_vw(){
        $images = Image::select('img','name')->where('category','=','Vw')->get();
        return new ImageResource(true, 'List Gallery Rafting', $images);
    }
    // public function logo(){
    //     $images = Image::select('img','name')->where('category','=','Logo')->get();
    //     return new ImageResource(true, 'List Logo', $images);
    // }
}
