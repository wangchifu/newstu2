<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','code','group_id','group_admin','class_num','ready','ready_user_id','situation','township_id',
    ];
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function ready_user()
    {
        return $this->belongsTo(User::class,'ready_user_id','id');
    }
}
