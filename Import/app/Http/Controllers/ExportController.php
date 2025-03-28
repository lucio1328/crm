<?php

namespace App\Http\Controllers;

use App\Exports\ClientExport;
use App\Services\Import\ImportService;
use App\Services\Import\RepartitionService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    

    public function __construct()
    {
        // $this->importService = $importService;
        // $this->repartitionSevice = $repartitionSevice;
    }

    public function index()
    {
        return view("import.index");
    }

    public function export(Request $request)
    {
        return Excel::download(new ClientExport($request->id),'Client.csv');
    }

   
}