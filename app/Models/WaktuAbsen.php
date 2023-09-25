<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaktuAbsen extends Model
{
    use HasFactory;
    protected $table = 'waktu_absen';
    protected $guarded = [''];
    public $timestamps = false;
}
