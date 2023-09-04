<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_PENDING = 0;
    const STATUS_DONE = 1;
    
    const STATUSES = [
        0 => "PENDING",
        1 => "DONE"
    ];
}
