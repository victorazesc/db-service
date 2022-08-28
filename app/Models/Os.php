<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Os extends Model
{
    use HasFactory;


    public function client(){
        return $this->hasOne(Client::class, 'id','client_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id','user_id');
    }
    public function selected_services()
    {
        return $this->belongsToMany(Services::class, 'os_services', 'os_id', 'service_id')->select('os_services.service_value', 'services.id', 'services.service_name');
    }



}
