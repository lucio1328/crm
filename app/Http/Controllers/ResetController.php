<?php

namespace App\Http\Controllers;

use App\Services\Reset\Reset;
use Illuminate\Http\Request;

class ResetController extends Controller
{
    protected $reset;

    public function __construct(Reset $reset)
    {
        $this->reset = $reset;
    }

    public function reinitialiser()
    {
        $result = $this->reset->resetTables();

        return redirect()->route('dashboard')->with([
            'message' => $result['message'],
            'success' => $result['success']
        ]);
    }

}
