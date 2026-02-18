<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{
   protected $fillable = ['content', 'embedding'];

   protected $casts = [
        'embedding' => 'array',
    ];


}
