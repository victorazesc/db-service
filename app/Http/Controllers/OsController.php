<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Os;
use App\Models\Services;
use App\Models\Os_service;
use App\Models\Status_os;
use Carbon\Carbon;
use PDF;
use stdClass;
use Exception;
use Illuminate\Support\Facades\Storage;

class OsController extends Controller
{

    public function index(Os $os)
    {
        $os = Os::where('os.id', $os->id)->with('client', 'user', 'selected_services')->first();

        return $os;
    }


    function valorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
    {

        // $valor = self::removerFormatacaoNumero( $valor );

        $singular = null;
        $plural = null;

        if ($bolExibirMoeda) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");


        if ($bolPalavraFeminina) {

            if ($valor == 1) {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            } else {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            }


            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }


        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);

        for ($i = 0; $i < count($inteiro); $i++) {
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "zero");
    }

    public function viewPdf(Request $request)
    {

        $data = Os::where('os.id', $request->id)->with('client', 'user', 'selected_services')->first();


        $date = Carbon::now()->format('YmdHisu');
        // Force locale
        date_default_timezone_set('America/Sao_Paulo');
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        setlocale(LC_TIME, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');


        // Create Carbon date
        $dt = Carbon::now();
        $data->today =  Carbon::parse($data->created_at)->format('d/m/Y');
        $data->year =  $dt->formatLocalized('%Y');
        $data->valueInText = $this->valorPorExtenso($data->amount);

        view()->share('os', $data);
        //  return view('pdf/os', $data);
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultMediaType' => 'screen'])->loadView('pdf/os', $data);

        $link = Storage::put('public/pdf/proposta' . $data->id . $date . '.pdf', $pdf->output());

        $file = new stdClass();
        $file->name = 'proposta' . $data->id . $date . '.pdf';
        $file->url = env('APP_URL') . '/storage/pdf/proposta' . $data->id . $date . '.pdf';
        $file->message = 'Arquivo gerado com sucesso !';
        $file->type = 'success';

        return response()->json($file);
    }

    public function getOs(Request $request)
    {
        if ($request['filter']) {

            $filter = json_decode($request['filter']);

            // $date_inital = $filter->create_date_initial ? Carbon::parse($filter->create_date_initial)->format('Y-m-d') : Carbon::now();
            // $date_final = $filter->create_date_final ? Carbon::parse($filter->create_date_initial)->format('Y-m-d') : Carbon::now();

            // return response()->json($date_inital);

            $allclients = os::orderBy('id', 'DESC')->with('client')->whereHas('client', function ($query) use ($filter) {

                if (isset($filter->name_client) && strlen($filter->name_client) > 0) {
                    $query->where('clients.name_client', 'like', '%'. $filter->name_client. '%');
                }
                if (isset($filter->created_at)) {
                    $query->where('os.created_at', 'LIKE' , '%'.$filter->created_at.'%');
                }
                if (isset($filter->amunt)) {
                    $query->where('os.created_at', 'LIKE' , '%'.$filter->amount.'%');
                }
         
          
                return $query;
            })

                ->paginate();

            return response()->json($allclients)->orderBy('id', 'DESC');
        }



        $query = Os::orderBy('id', 'DESC')->with('client')->paginate(25);
        return response()->json($query);
    }

    public function getStatusOs()
    {
        $query = Status_os::all();

        return response()->json($query);
    }

    public function deleteOs(Os $os)
    {
        Os::where('id', $os->id)->delete();
    }

    public function editOs(Request $request)
    {
        $os = Os::find($request->id);
        $os->status = 0;
        $os->client_id = $request->client_id;
        $os->user_id = $request->user()->id;
        $os->comments = $request->comments;
        $os->amount = $request->amount;
        $os->monthly = $request->monthly;
        $os->save();


        Os_service::where('os_id', $request->id)->forceDelete();

        foreach ($request->selected_services as $i) {
            $service = new Os_service();
            $service->os_id =  $os->id;
            $service->service_id = $i['id'];
            $service->service_value = $i['service_value'];
            $service->save();
        }

        return response()->json($os);
    }
    public function addOs(Request $request)
    {


        $os = new Os();
        $os->status = 0;
        $os->client_id = $request->client;
        $os->user_id = $request->user()->id;
        $os->comments = $request->comments;
        $os->amount = $request->amount;
        $os->monthly = $request->monthly;
        $os->save();


        foreach ($request->selected_services as $i) {
            $service = new OS_service();
            $service->os_id =  $os->id;
            $service->service_id = $i['id'];
            $service->service_value = $i['service_value'];
            $service->save();
        }
        app('App\Http\Controllers\Logs\LogsController')->registerLog($os->id, 'Criação de Proposta', '/proposal', 'create');
        return response()->json(
            [
                'os' => $os,
                'message' => "Cliente Adicionado com Sucesso",
                'type' => "success"
            ]
        );
    }
}
