<?php

namespace App\Http\Controllers\Receipts;

use App\Models\Receipt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReceiptsController extends Controller
{
    public function getReceipts (){

        $all = Receipt::join('clients', 'clients.id', '=', 'receipts.client_id')
        ->select('clients.name_client', 'receipts.*')
        ->paginate(15);


        return response()->json($all);



    }
}
