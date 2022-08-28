<?php

namespace App\Http\Controllers\Public_controller;

use App\Http\Controllers\Controller;
use App\Mail\gdfContact;
use Illuminate\Http\Request;
use App\Mail\contact;
use Illuminate\Support\Facades\Mail;


class Public_controller extends Controller
{
    public function send_mail (Request $request)
    {
        foreach (['victorazesc@gmail.com', 'qualidade@db-assuntosregulatorios.com', 'qualidade.1@db-assuntosregulatorios.com'] as $recipient) {
            Mail::to($recipient)->send(new contact($request));
        }
    }
    public function send_mail_gdf (Request $request)
    {//
        foreach (['victorazesc@gmail.com', 'fabiola@gdfservicos.com.br'] as $recipient) {
            Mail::to($recipient)->send(new gdfContact($request));
        }
    }
}
