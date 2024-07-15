<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'semester_year',
        'no',
        'class',
        'num',
        'sex',
        'name',
        'id_number',
        'old_school',
        'type',
        'special',
        'subtract',
        'another_no',
        'ps',
        'with_teacher',
        'without_teacher',
    ];
    public function w_teacher()
    {
        return $this->belongsTo(Teacher::class,'with_teacher','id');
    }
    public function wo_teacher()
    {
        return $this->belongsTo(Teacher::class,'without_teacher','id');
    }
}
