<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Services\Import\ImportService;

class ImportController extends Controller
{
    protected $import;

    public function __construct(ImportService $import)
    {
        $this->import = $import;
    }

    public function index()
    {
        return view('import.charger');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('csv_file') && $request->file('csv_file')->isValid()) {
            $file = $request->file('csv_file');
            $filePath = $file->storeAs('csv', 'data.csv', 'local');

            $result = $this->import->importFromCsv(storage_path("app/csv/data.csv"));

            Session::flash('success', 'Importation réussie');
            Session::flash('import_message', $result);
        } else {
            Session::flash('error', 'Erreur lors de l\'importation du fichier CSV.');
        }

        return redirect()->route('import.index');
    }
}

