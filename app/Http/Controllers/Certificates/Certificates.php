<?php

namespace App\Http\Controllers\Certificates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\notification;

class Certificates extends Controller
{
    public function getCertificates(Request $request)
    {
        if ($request['filter']) {

            $filter = json_decode($request['filter']);
            $allclients = Certificate::orderBy('id', 'DESC')->with('client')->whereHas('client', function ($query) use ($filter) {

                if (isset($filter->emission_date) && strlen($filter->emission_date) > 0) {
                    // $date = Carbon::parse($filter->emission_date)->format('Y-m-d');
                    $query->where('certificates.emission_date', $filter->emission_date);
                }
                if (isset($filter->due_date) && strlen($filter->due_date) > 0) {
                    $date = Carbon::parse($filter->due_date)->format('Y-m-d');
                    $query->where('certificates.due_date', $date);
                }
                if (isset($filter->alert_days) && strlen($filter->alert_days) > 0) {
                    $query->where('certificates.alert_days', $filter->alert_days);
                }
                if (isset($filter->certificate_number)) {
                    $query->where('certificates.certificate_number', 'like', '%' . $filter->certificate_number . '%');
                }
                if (isset($filter->certificate_name)) {
                    $query->where('certificates.certificate_name', 'like', '%' . $filter->certificate_name . '%');
                }
                if (isset($filter->client)) {
                    $query->where('clients.id', $filter->client);
                }

                return $query;
            })

                ->paginate();

            return response()->json($allclients);
        }
        if ($request['all']) {
            $all = Certificate::with('client')->orderBy('id', 'DESC')->where('certificate_name', 'like', '%' . $request['data'] . '%')->get();
        } else {
            $all = Certificate::with('client')->orderBy('id', 'DESC')->paginate(25);
        }

        return response()->json($all);
    }

    public function editCertificate(Request $request)
    {
        $due_date = date('Y-m-d H:i:s', strtotime($request->due_date));
        $emission_date = date('Y-m-d H:i:s', strtotime($request->emission_date));
        $today = date('Y-m-d H:i:s', strtotime(Carbon::now()));
        $current_max_day = date('Y-m-d H:i:s', strtotime('-' . $request->alert_days . 'days', strtotime($due_date)));

        if (strtotime($due_date) < strtotime($today)) {
            $status = 'Vencido';
        } elseif (strtotime($today) < strtotime($current_max_day)) {
            $status = 'Vigente';
        } elseif (strtotime($today) > strtotime($current_max_day)) {
            $status = 'A Vencer';
        };


        $certificate = Certificate::where('id', $request->id)->first();
        if ($request->certificate_number === $certificate->certificate_number || Certificate::where('certificate_number', $request->certificate_number)->count() == 0) {
            Certificate::where('id', $request->id)->update([
                "emission_date" =>  $emission_date,
                "client_id" => $request->client['id'],
                "certificate_number" => $request->certificate_number,
                "certificate_name" => $request->certificate_name,
                "alert_days" => $request->alert_days,
                "status" => $status,
                "due_date" =>  $due_date,
                "email_notification" => $request->email_notification,
                "obs" => $request->obs,
            ]);
            app('App\Http\Controllers\Logs\LogsController')->registerLog($request->id, 'Edição de certidão', '/certificates', 'update');
            return response()->json([
                "message" => "Certidão Editada com sucesso!",
                "type" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "Esta certidão ja esta cadastrada!",
                "type" => "danger",
            ]);
        }
    }

    public function addCertificate(Request $request)
    {


        $due_date = date('Y-m-d H:i:s', strtotime($request->due_date));
        $emission_date = date('Y-m-d H:i:s', strtotime($request->emission_date));

        $today = date('Y-m-d H:i:s', strtotime(Carbon::now()));

        $current_max_day = date('Y-m-d H:i:s', strtotime('-' . $request->alert_days . 'days', strtotime($due_date)));


        if (strtotime($due_date) < strtotime($today)) {
            $status = 'Vencido';
        } elseif (strtotime($today) < strtotime($current_max_day)) {
            $status = 'Vigente';
        } elseif (strtotime($today) > strtotime($current_max_day)) {
            $status = 'A Vencer';
        };

        if (Certificate::where('certificate_number', $request->certificate_number)->count() == 0) {
            $return = Certificate::create([
                "emission_date" => $emission_date,
                "client_id" => $request->client['id'],
                "certificate_number" => $request->certificate_number,
                "certificate_name" => $request->certificate_name,
                "alert_days" => $request->alert_days,
                "status" => $status,
                "due_date" => $due_date,
                "email_notification" => $request->email_notification,
                "obs" => $request->obs,
            ]);


            app('App\Http\Controllers\Logs\LogsController')->registerLog($return->id, 'Criação de certidão', '/certificates', 'create');
            return response()->json([
                "message" => "Certidão adicionada com sucesso!",
                "type" => "success",
            ]);
        } else {
            return response()->json([
                "message" => "Esta certidão ja está cadastrada!",
                "type" => "danger",
            ]);
        }
    }

    public function deleteCertificate(Request $request)
    {

        app('App\Http\Controllers\Logs\LogsController')->registerLog($request->id, 'Certidão deletada', '/certificates', 'delete');
        return Certificate::where('id', $request->id)->delete();
    }

    public function changeStatus()
    {

        $all = Certificate::orderBy('id', 'DESC')->get();

        foreach ($all as $i) {

            $today = date('Y-m-d H:i:s', strtotime(Carbon::now()));

            $current_max_day = date('Y-m-d H:i:s', strtotime('-' . $i->alert_days . 'days', strtotime($i->due_date)));

            if (strtotime($i->due_date) < strtotime($today)) {
                $status = 'Vencido';
            } elseif (strtotime($today) < strtotime($current_max_day)) {
                $status = 'Vigente';
            } elseif (strtotime($today) > strtotime($current_max_day)) {
                $status = 'A Vencer';
            };

            $certificate = Certificate::where('id', $i->id)->update(
                [
                    "status" => $status,
                ]
            );
        }

        $certificates = Certificate::where('status', 'Vencido')
            ->orWhere('status', 'A Vencer')
            ->with('client')
            ->where('email_notification', true)
            ->get();

        if (sizeof($certificates) > 0) {
            $sendMail = Mail::to('victorazesc@gmail.com', 'Victor Azevedo')->send(new notification($certificates));
        }
        return sizeof($certificates);
    }
}
