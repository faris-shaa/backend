<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'arabic_name',
        'facebook',
        'twitter',
        'instagram',
        'short_description_english',
        'short_description_arabic',
        'long_description_english',
        'long_description_arabic'
    ];

    protected $table = 'organizer_details';

}
