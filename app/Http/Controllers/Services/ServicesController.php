<?php

namespace App\Http\Controllers\Services;

use App\Models\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function getServices(Request $request)
    {

     


        if (isset($request['all'])) {
            $all = Services::orderBy('id', 'DESC')->get();
        } else {
            $all = Services::orderBy('id', 'DESC')->paginate(15);
        }
        if ($request['filter']) {

            $filter = json_decode($request['filter']);
            $allclients = Services::orderBy('id', 'DESC')
                ->where(function ($query) use ($filter) {
                    foreach ($filter as $key => $value) {
                        if (strlen($value) > 0) {

                            $query->where($key, 'like', '%' . $value . '%');
                        }
                    }
                    return $query;
                })

                ->paginate();

            return response()->json($allclients);
        }

        if(isset($request['search'])) {
            $all = Services::where('service_name','LIKE', '%'.$request['search'].'%')->get();
        }


        return response()->json($all);
    }

    public function addService(Request $request)
    {


        $query = new Services();
        $query->service_name = $request->service_name;
        $query->service_value = $request->service_value ?? null;
        $query->service_description = $request->service_description;
        $query->obs = $request->obs;
        $query->save();

        return response()->json($query);
    }

    public function deleteService(Request $request)
    {

        $id = $request['id'];
        $Service = Services::findOrFail($id);
        $Service->delete();
    }


    public function editService(Request $request)
    {

        $id = $request['id'];

        $service = Services::findOrFail($id);
        $service->service_name = $request->service_name;
        $service->service_value = number_format($request->service_value, 2, '.', '');
        $service->service_description = $request->service_description;
        $service->obs = $request->obs;


        $service->save();
        return response($service, 200);
    }
}
