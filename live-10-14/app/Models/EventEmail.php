<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'ss',
        'email', 
    ];

    protected $table = 'event_emails'; 
}
