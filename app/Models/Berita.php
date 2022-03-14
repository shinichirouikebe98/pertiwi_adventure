<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title','img','slug','news'];

    public function user(){
        return $this->BelongsTo(User::class);
    }

    public function getImageAttribute($image)
    {
        return asset('storage/berita/' . $image);
    }
    
    public function getCreatedAtAttribute($created_at)
    {   
        $value = \Carbon\Carbon::parse($created_at);
        $parse = $value->locale('id');
        return $parse->translatedFormat('l, d F Y');
    }
    
    public function getUpdatedAtAttribute($updated_at)
    {   
        $value = \Carbon\Carbon::parse($updated_at);
        $parse = $value->locale('id');
        return $parse->translatedFormat('l, d F Y');
    }
}
