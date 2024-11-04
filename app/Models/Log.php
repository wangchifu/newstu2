<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = [
        'message','user_id','ip','for_code','group_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
