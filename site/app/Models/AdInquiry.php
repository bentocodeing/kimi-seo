<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdInquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'company',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
