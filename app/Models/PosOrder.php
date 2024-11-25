<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWallet;

class PosOrder extends Model
{
    use HasFactory;

    protected $table = 'pos_order';

    

}
