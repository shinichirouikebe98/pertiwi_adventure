<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Promotion;
use App\Models\Berita;


class DashboardController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $video      = Video::count();
        $promotion   = Promotion::count();
        $berita = Berita::count();
        // $users      = User::count();

        return response()->json([
            'success' => true,
            'message' => 'List Count Data Table',  
            'data' => [
                'video'      => $video,
                'promotion'   => $promotion,
                'berita' => $berita
            ],
        ], 200);
    }
}