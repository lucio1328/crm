<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Import;

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
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:10048',
        ]);

        $file = $request->file('csv_file');
        $path = $file->store('uploads', 'public');

        return redirect()->route('import.index')->with('success', 'Fichier importé avec succès !');
    }
}

