<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('user')->latest()->paginate(5);

        //return with Api Resource
        return new VideoResource(true, 'List Data Berita', $videos);
    }
}
