<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Log;
use Carbon\Carbon;
use PDF;
use stdClass;
use Exception;

class LogsController extends Controller
{
    public function registerLog($id = 0, $extra = null, $path = null, $action = null)
    {
        $user_id = Auth::id();

        Log::create([
            'action' => $action,
            'user_id' => $user_id,
            'route' => $path ?? '',
            'extra_info' => $extra ?? '',
            'send_id' => $id ?? 0
        ]);
    }

    public function logs(Request $request)
    {

        if ($request['filter']) {

            $filter = json_decode($request['filter']);

            $filtered = Log::with('user')->orderBy('logs.id', 'DESC')->whereHas('user', function ($query) use ($filter) {

                foreach ($filter as $key => $value) {

                    if (isset($key->first_name)) {
                        $query->where('users.first_name', 'like', '%' . $value . '%');
                    }
                    if (isset($key->last_name)) {
                        $query->where('users.last_name', 'like', '%' . $value . '%');
                    }

                    switch ($value) {
                        case 'Criar':
                            $value = 'create';
                            break;
                        case 'Editar':
                            $value = 'update';
                            break;
                        case 'Deletar':
                            $value = 'delete';
                            break;
                    }

                    $query->where($key, 'like', '%' . $value . '%');
                }



                // $query->where('logs.action', 'like', '%update%');
            })->paginate();


            // $filtered = Log::orderBy('logs.id', 'DESC')->join('users','users.id', '=', 'logs.user_id')
            //     ->where(function ($query) use ($filter) {
            //         foreach ($filter as $key => $value) {,
            //             if (strlen($value) > 0) {

            //                 switch ($value) {
            //                     case 'Criar':
            //                         $value = 'create';
            //                         break;
            //                     case 'Editar':
            //                         $value = 'update';
            //                         break;
            //                     case 'Deletar':
            //                         $value = 'delete';
            //                         break;
            //                 }

            //                 $query->where($key, 'like', '%' . $value . '%');
            //             }
            //         }
            //         return $query;
            //     })

            //     ->paginate();





            // return $request->filter['user'];
            return response()->json($filtered);
        }


        if (isset($request->where)) {
            return response()->json(Log::where('user_id', Auth::user()->id)->with('user')->orderBy('id', 'desc')->paginate(25));
        } else {
            return response()->json(Log::with('user')->orderBy('id', 'desc')->paginate(25));
        }
    }

    public function clearLogs(Request $request)
    {
        try {
            Log::truncate();

            $message = new stdClass();
            $message->message = 'Logs deletados com Sucesso!';
            $message->type = 'success';

            return response()->json($message);
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }

    public function deleteFile(Request $request)
    {
        try {
            Storage::delete('public/pdf/' . $request->name);
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }

    public function generatePdf(Request $request)
    {

        $data = Log::with('user')->get();

        $date = Carbon::now()->format('YmdHisu');


        view()->share('logs', $data);
        // return view('pdf\myPDF', $data);
        $pdf = PDF::loadView('pdf/myPDF', $data)->setPaper('a4', 'landscape');

        $link = Storage::put('public/pdf/relatorios-de-logs' . $date . '.pdf', $pdf->output());

        $file = new stdClass();
        $file->name = 'relatorios-de-logs' . $date . '.pdf';
        $file->url = env('APP_URL') . '/storage/pdf/relatorios-de-logs' . $date . '.pdf';
        $file->message = 'Arquivo gerado com sucesso !';
        $file->type = 'success';

        return response()->json($file);
    }
}
