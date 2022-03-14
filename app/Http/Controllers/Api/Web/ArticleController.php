<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function cardIntroArticle(){
        $articles = Article::where('category','=','Introduction')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
    public function bannerPaintballArticle(){
        $articles = Article::where('category','=','Paintball_Banner')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
    public function bannerAtvArticle(){
        $articles = Article::where('category','=','Atv_Banner')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
    public function bannerRaftingArticle(){
        $articles = Article::where('category','=','Rafting_Banner')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
    public function bestSeller(){
        $articles = Article::where('category','=','Best_Seller')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
    public function homeBanner(){
        $articles = Article::where('category','=','Home_Banner')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
    public function paintballActivities(){
        $articles = Article::where('category','=','Paintball_Activities')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
    public function raftingActivities(){
        $articles = Article::where('category','=','Rafting_Activities')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
    public function atvActivities(){
        $articles = Article::where('category','=','Atv_Activities')->get('article');
        return new ArticleResource(true, 'Article Berhasil di dapat', $articles);
    }
}
