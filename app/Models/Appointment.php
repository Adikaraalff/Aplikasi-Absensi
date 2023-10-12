<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = "appointments";

    protected $fillable = [
        'id', 'name',
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'id_appointment', 'id');
    }
}
