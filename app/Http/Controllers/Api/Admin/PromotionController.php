<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Promotion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PromotionResource;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        //get categories
        $promotions = Promotion::with('user')->when(request()->q, function($promotions) {
            $promotions = $promotions->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        
        //return with Api Resource
        return new PromotionResource(true, 'List Data Promotion', $promotions);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'title'  => 'required|unique:promotions|string',
            'description'   => 'required|string'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('img');
        $image->storeAs('public/promotion', $image->hashName());

        //create category
        $promotion = Promotion::create([
            'img'=> $image->hashName(),
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->guard('api')->user()->id,
            'slug' => Str::slug($request->title, '-'),
        ]);

        if($promotion) {
            //return success with Api Resource
            return new PromotionResource(true, 'Data Promotion Berhasil Disimpan!', $promotion);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Data Promotion Gagal Disimpan!', null);
    }
    public function show($id)
    {
        $promotion = Promotion::whereId($id)->first();
        
        if($promotion) {
            //return success with Api Resource
            return new PromotionResource(true, 'Detail Data Promotion!', $promotion);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Detail Data Promotion Tidak DItemukan!', null);
    }
    public function update(Request $request, Promotion $promotion)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required|string|unique:promotions,title,'.$promotion->id,
            'description'   => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('img')) {

            //remove old image
            Storage::disk('local')->delete('public/promotion/'.basename($promotion->img));
        
            //upload new image
            $image = $request->file('img');
            $image->storeAs('public/promotion', $image->hashName());

            //update promo with new image
            $promotion->update([
                'title'=> $request->title,
                'img'=> $image->hashName(),
                'description' => $request->description,
                'slug' => Str::slug($request->title, '-'),
                'user_id'=> auth()->guard('api')->user()->id
            ]);

        }

        //update promo without image
        $promotion->update([
            'title'=> $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title, '-'),
            'user_id'=> auth()->guard('api')->user()->id
        ]);

        if($promotion) {
            //return success with Api Resource
            return new PromotionResource(true, 'Data Promotion Berhasil Diupdate!', $promotion);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Data Promotion Gagal Diupdate!', null);
    }

    public function destroy(Promotion $promotion)
    {
        //remove image
        Storage::disk('local')->delete('public/promotion/'.basename($promotion->img));

        if($promotion->delete()) {
            //return success with Api Resource
            return new PromotionResource(true, 'Data Promotion Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Data Promotion Gagal Dihapus!', null);
    }
}
