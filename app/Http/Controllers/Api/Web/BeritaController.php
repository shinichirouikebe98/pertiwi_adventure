<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Http\Resources\BeritaResource;

class BeritaController extends Controller
{
    public function index(){
        $beritas = Berita::with('user')->latest()->paginate(5);
        //return with Api Resource
        return new BeritaResource(true, 'List Data Berita', $beritas);
    }
    public function show($slug){
        $berita = Berita::with('user')->where('slug',$slug)->first();
        
        if($berita) {
            //return success with Api Resource
            return new BeritaResource(true, 'Detail Data Berita!', $berita);
        }

        //return failed with Api Resource
        return new BeritaResource(false, 'Detail Data Berita Tidak DItemukan!', null);
    }
    public function getBeritaSlug(){
        $beritas = Berita::select('slug','updated_at')->get();
        //return with Api Resource
        return $beritas; 
    }

    
}
