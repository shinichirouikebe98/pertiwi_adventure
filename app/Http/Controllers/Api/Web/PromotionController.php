<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PromotionResource;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('user')->latest()->paginate(5);

        //return with Api Resource
        return new PromotionResource(true, 'List Data Berita', $promotions);
    }
    public function show($id)
    {
        $promotion = Promotion::with('user')->whereId($id)->first();
        
        if($promotion) {
            //return success with Api Resource
            return new PromotionResource(true, 'Detail Data Promotion!', $promotion);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Detail Data Promotion Tidak DItemukan!', null);
    }
    public function getPromotionSlug(){
        $promotions = Promotion::select('slug','updated_at')->get();

        //return with Api Resource
        return $promotions;
    }

}
