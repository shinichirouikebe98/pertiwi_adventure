<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        //get categories
        $articles = Article::with('user')->when(request()->q, function($articles) {
            $articles = $articles->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(7);
        
        //return with Api Resource
        return new ArticleResource(true, 'List Data Article', $articles);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article'  => 'required|string',
            'category'   => 'required|string',
            'title' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create article
        $article = Article::create([
            'article' => $request->article,
            'category' => $request->category,
            'title' => $request->title,
            'user_id' => auth()->guard('api')->user()->id,
        ]);

        if($article) {
            //return success with Api Resource
            return new ArticleResource(true, 'Data Article Berhasil Disimpan!', $article);
        }

        //return failed with Api Resource
        return new ArticleResource(false, 'Data Article Gagal Disimpan!', null);
    }
    public function show($id)
    {
        $article = Article::whereId($id)->first();
        
        if($article) {
            //return success with Api Resource
            return new ArticleResource(true, 'Detail Data Article!', $article);
        }

        //return failed with Api Resource
        return new ArticleResource(false, 'Detail Data Article Tidak DItemukan!', null);
    }
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'article'  => 'required|string',
            'category'   => 'required|string',
            'title' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } 
        
        $article->update([
            'article' => $request->article,
            'category' => $request->category,
            'title' => $request->title,
            'user_id' => auth()->guard('api')->user()->id,
        ]);


        if($article) {
            //return success with Api Resource
            return new ArticleResource(true, 'Data Article Berhasil Diupdate!', $article);
        }

        //return failed with Api Resource
        return new ArticleResource(false, 'Data Article Gagal Diupdate!', null);
    }

    public function destroy(Article $article)
    {

        if($article->delete()) {
            //return success with Api Resource
            return new ArticleResource(true, 'Data Article Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new ArticleResource(false, 'Data Article Gagal Dihapus!', null);
    }
}
