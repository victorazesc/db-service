<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\User;
use App\Models\Service;


class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $id = auth()->user()->id;

        $dataUser = User::where('id', $id)->first();
        $CountClients = Client::all()->Count();
        $CountProducts = Product::all()->Count();
        $CountReceipts = Receipt::all()->Count();
        $CountServices = Service::all()->Count();

        $recentProducts = Product::orderBy('id', 'DESC')->limit(5)->get();


        return response()->json([
            'clients' => $CountClients,
            'products' => $CountProducts,
            'receipts' => $CountReceipts,
            'services' => $CountServices,
            'recent_products' => $recentProducts,
            'dataUser' => $dataUser


        ]);
    }
}
