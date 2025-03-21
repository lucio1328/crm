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

        if ($result['success']) {
            return response()->json(['message' => $result['message']], 200);
        }

        return response()->json(['message' => $result['message']], 500);
    }
}
