<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientsController extends Controller
{
    public function GetClients(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request['filter']) {

            $filter = json_decode($request['filter']);
            $allclients = Client::orderBy('id', 'DESC')
                ->where(function ($query) use ($filter) {
                    foreach ($filter as $key => $value) {
                        if (strlen($value) > 0) {

                            switch ($value) {
                                case 'document_client':
                                    $value = preg_replace('/[^0-9]/', '', $value);
                                    break;
                                case 'telephone_client':
                                    $value = preg_replace('/[^0-9]/', '', $value);
                                    break;
                                case 'cellphone_client':
                                    $value = preg_replace('/[^0-9]/', '', $value);
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
        if ($request['all']) {
            $allclients = Client::orderBy('id', 'DESC')->where('name_client', 'like', '%' . $request['data'] . '%')->get();
        } else {
            $allclients = Client::orderBy('id', 'DESC')->paginate();
        }
        return response()->json($allclients);
    }

    public function GetClientById(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->id;
        $client = Client::where('id', $id)->first();
        return response()->json($client);
    }

    public function addClient(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name_client' => 'required|string|max:255',
        ]);

        if (Client::where('document_client', $request->document_client)->count() == 0) {

            $value = preg_replace('/[^0-9]/', '', $request->document_client);

            $return = Client::create([
                'name_client' => $request->name_client,
                'document_client'  => $value,
                'telephone_client' => preg_replace('/[^0-9]/', '', $request->telephone_client),
                'cellphone_client' => preg_replace('/[^0-9]/', '', $request->cellphone_client),
                'email_client' => $request->email_client,
                'street_client' => $request->street_client,
                'cep_client' => $request->cep_client,
                'city_client' => $request->city_client,
                'district_client' => $request->district_client,
                'juridic_person' => $request->juridic_person,
                'number_client' => $request->number_client,
                'complement' => $request->complement,
                'state_client' => $request->state_client,
                'obs' => $request->obs,
                'created_at' => Carbon::Now(),
            ]);

            app('App\Http\Controllers\Logs\LogsController')->registerLog($return->id, 'Criação de cliente', '/clients', 'create');
            return response()->json(
                [
                    'message' => "Cliente Adicionado com Sucesso",
                    'type' => "success"
                ]
            );
        } else {
            return response()->json(
                [
                    'message' => "Cliente já existente com esse número de documento",
                    'type' => "danger"
                ]
            );
        }
    }

    public function deletClient(Request $request)
    {

        $id = $request['id'];
        app('App\Http\Controllers\Logs\LogsController')->registerLog($id, 'Cliente deletado', '/clients', 'delete');
        $certificate = certificate::where('client_id', $id)->delete();
        $client = Client::findOrFail($id);
        $client->delete();
    }
    public function editClient(Request $request)
    {

        $id = $request['id'];

        $client = Client::findOrFail($id);
        $client_document_client = preg_replace('/[^0-9]/', '', $client->document_client);
        $request_document_client = preg_replace('/[^0-9]/', '', $request->document_client);

        if ($client === null) {

            return response()->json([
                "message" => "Erro ao Editar Cliente ",
                "type" => "danger"
            ]);
        } else {
            if ($request_document_client === $client_document_client || Client::where('document_client', $request_document_client)->count() == 0) {
                $client->name_client = $request->name_client;
                $client->document_client = $request_document_client;
                $client->telephone_client = preg_replace('/[^0-9]/', '', $request->telephone_client);
                $client->cellphone_client = preg_replace('/[^0-9]/', '', $request->cellphone_client);
                $client->email_client = $request->email_client;
                $client->street_client  = $request->street_client;
                $client->complement = $request->complement;
                $client->cep_client = $request->cep_client;
                $client->city_client = $request->city_client;
                $client->district_client = $request->district_client;
                $client->juridic_person = $request->juridic_person;
                $client->number_client = $request->number_client;
                $client->state_client = $request->state_client;
                $client->obs = $request->obs;
                $client->save();

                app('App\Http\Controllers\Logs\LogsController')->registerLog($id, 'Edição de cliente', '/clients', 'update');

                return response()->json([
                    "message" => "Cliente Editado com Sucesso!",
                    "type" => "success"
                ]);
            } else {
                return response()->json(
                    [
                        'message' => "Cliente já existente com esse número de documento",
                        'type' => "danger"
                    ]
                );
            }
        }
    }
}
