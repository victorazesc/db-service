<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfilesController extends Controller
{
    public function getProfiles (Request $request){

        $all = Profile::all();
        $null = array(['value' => null, 'text' => 'Please select some item']);
        foreach($all as $i){
          $i->value = $i->id;
          $i->text = $i->profile_name;
        }
       
        return response()->json($all);

         
    }
}
