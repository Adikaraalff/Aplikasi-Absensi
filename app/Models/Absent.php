<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absent extends Model
{
    use HasFactory;
    protected $table = "absents";

    protected $fillable = [
        'user_id', 'status',
    ];

    public function User() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
