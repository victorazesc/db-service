<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    protected $fillable = [
      "id", "emission_date",
      "client_id",
      "certificate_number",
      "certificate_name",
      "alert_days",
      "due_date",
      "email_notification",
      "created_at",
      "updated_at",
      "obs",
      "status"
    ];

    public function client(){
      return $this->hasOne(Client::class, 'id','client_id');
  }
}
