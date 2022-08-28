<?php

namespace App\Http\Controllers;

use Validator, Redirect, Response, File;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('profile')->get();

        return response()->json($users);
    }

    public function editUser(Request $request)
    {

        
        $var = json_decode($request->user);
        
        if ($files = $request->file('file')) {
            
            $image_name = Carbon::now();
            $image_name = $valor = str_replace("-", "", $image_name);
            $image_name = $valor = str_replace(" ", "", $image_name);
            $image_name = $valor = str_replace(":", "", $image_name);
            
            $path = image::make($request->file('file'))->save('storage/users/' . $image_name . '.jpg');
            
            $path = '/users/' . $image_name . '.jpg';
        }
        
    //    return response()->json($path);
        
        //store your file into database
        $user = User::find($var->id);
        $user->first_name = $var->first_name;
        $user->last_name = $var->last_name;
        $user->document = $var->document;
        $user->profile_id = $var->profile_id;
        $user->email = $var->email;
        if (isset($path)) {
            if (file_exists('storage/'.$user->profile_photo_path)) {
                unlink('storage/'.$user->profile_photo_path);
            }             
            $user->profile_photo_path = $path;
        }
        $user->save();

        return response()->json($user);
    }

    public function changePassword(Request $request)
    {
        User::where('id', Auth::user()->id)->update([
            'password' => Hash::make($request->password)
        ]);
        return response()->json($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
