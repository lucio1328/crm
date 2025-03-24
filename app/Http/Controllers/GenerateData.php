<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class GenerateData extends Controller
{
    public function index()
    {
        return view("generate.index");
    }

    public function generate(Request $request)
    {
         
        $data = $request->input('tables', []);
 
        foreach ($data as $table => $count) {
             
            if ($count > 0) { 
                for ($i = 0; $i < $count; $i++) {
                    Artisan::call('db:seed', [
                        '--class' => ucfirst(camel_case($table)) . 'DummyTableSeeder'
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Les données ont été générées avec succès !');
    }
}
