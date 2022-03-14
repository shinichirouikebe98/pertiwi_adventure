<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','name', 'img','category','keterangan'
    ];
    public function user(){
        return $this->BelongsTo(User::class);
    }

    public function getImageAttribute($image)
    {
        return asset('storage/image/' . $image);
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
