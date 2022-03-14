<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug','description','link','user_id'];

    public function user(){
        return $this->BelongsTo(User::class);
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
