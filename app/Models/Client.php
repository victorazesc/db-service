<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_client',
        'document_client',
        'telephone_client',
        'cellphone_client',
        'email_client',
        'street_client',
        'complement',
        'cep_client',
        'city_client',
        'district_client',
        'obs',
        'number_client',
        'state_client',
    ];

}
