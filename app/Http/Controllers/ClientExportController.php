<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ClientExport;

use Maatwebsite\Excel\Facades\Excel;

class ClientExportController extends Controller
{
    public function export($external_id)
    {
        return Excel::download(new ClientExport($external_id),'Client.csv');
    }

}