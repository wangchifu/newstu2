<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','code','group_id','group_admin','ready',
    ];
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
