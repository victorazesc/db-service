<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'user_id',
        'route',
        'extra_info',
        'send_id'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id','user_id');
    }
}
