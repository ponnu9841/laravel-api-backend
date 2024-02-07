<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SubCategory extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
