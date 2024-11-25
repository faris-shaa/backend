<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    
    protected $fillable = [
        'name',
        'image',
        'status',
        'arabic_name', 
        'image'
    ];

    protected $table = 'city';

    protected $appends = ['imagePath'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }
}
