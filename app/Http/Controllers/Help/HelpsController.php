<?php

namespace App\Http\Controllers\Help;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Help;
use App\Models\Notification;
use App\Models\Client;
use Carbon\Carbon;

class HelpsController extends Controller
{
    public function getHelps(Request $request)
    {

        if ($request['filter']) {

            $filter = json_decode($request['filter']);
            $allclients = Help::orderBy('id', 'DESC')
                ->where(function ($query) use ($filter) {
                    foreach ($filter as $key => $value) {
                        if (strlen($value) > 0) {

                            switch ($value) {
                                case 'Pública':
                                    $value = 0;
                                    break;
                                case 'Restrita':
                                    $value = 1;
                                    break;
                                case 'Todas':
                                    $value = '';
                                    break;
                            }

                            $query->where($key, 'like', '%' . $value . '%');
                        }
                    }
                    return $query;
                })

                ->paginate();

            return response()->json($allclients);
        }

        if (isset($request->frequent)) {
            $all = Help::where('restrict', 0)->whereNotNull('response')->limit(5)->get();
        } else {
            $all = Help::with('client')->orderBy('id', 'DESC')->paginate(10);
        }
        return response()->json($all);
    }
    public function sendHelp(Request $request)
    {

        if (isset($request->client_email)) {

            $client = Client::where('email_client', $request->client_email)->first();

            $newhelp = new Help();
            $newhelp->client_name = $request->client_name;
            $newhelp->client_phone = $request->client_phone;
            $newhelp->client_email = $request->client_email;
            $newhelp->send_date = Carbon::now();
            $newhelp->question = $request->question;
            $newhelp->restrict = 1;
            if ($client) {
                $newhelp->client_id = $client->id;
            }
            $newhelp->save();

            $notification = new Notification();
            $notification->ref_id = $newhelp->id;
            $notification->message = 'Você recebeu uma nova pergunta do cliente ' . $request->client_name . ' !';
            $notification->subject = 'Nova pergunta!';
            $notification->type = 'HELP';
            $notification->icon = 'question';
            $notification->link = '/help';
            $notification->save();

            return response()->json(['sucess' => true]);
        }

        $select = Help::where('question', 'LIKE', '%' . $request->question . '%')->where('restrict', 0)->whereNotNull('response')->get();

        if (sizeof($select) == 0) {
            return response()->json(['none' => true]);
        }

        return response()->json($select);
    }

    public function addHelp(Request $request)
    {
        $help = Help::create([
            'question' => $request->question,
            'send_date' => Carbon::now(),
            'response' => $request->response,
            'response_date' => Carbon::now(),
            'status' => 1,
            'restrict' => $request->restrict,
        ]);

        return response()->json($help);
    }

    public function responseHelpQuestion(Request $request)
    {

        if (isset($request->sendEmail) && $request->sendEmail) {

            $details = [
                'title' => $request->question,
                'body' => $request->response
            ];

            \Mail::to($request->client_email)->send(new \App\Mail\sendResponse($details));
        }
        $help = Help::where('id', $request->id)->update([
            'response' => $request->response,
            'response_date' => Carbon::now(),
            'status' => 1,
        ]);
    }

    public function editHelp(Request $request)
    {
        $help = Help::where('id', $request->id)->update([
            'question' => $request->question,
            'response' => $request->response,
            'response_date' => Carbon::now(),
            'status' => 1,
            'restrict' => $request->restrict,
        ]);
    }

    public function deleteHelp(Request $request)
    {
        $help = Help::where('id', $request->id)->delete();
    }
}
