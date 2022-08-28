<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'send_date',
        'response_date',
        'response',
        'status',
        'client_email',
        'client_phone',
        'client_name',
        'restrict',

    ];

    public function client(){
        return $this->hasOne(Client::class, 'id','client_id');
    }
}
