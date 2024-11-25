<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'status',
        'ar_name',
        'app_icon'
    ];

    protected $table = 'category';
    protected $appends = ['imagePath'];
    
    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }
}
