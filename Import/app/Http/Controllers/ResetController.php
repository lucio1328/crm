<?php

namespace App\Http\Controllers;
use App\Services\Reset\ResetService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class ResetController extends Controller
{   
    protected $resetService;

    public function __construct(ResetService $resetService)
    {   
        $this->middleware("can.reset.database");
        $this->resetService = $resetService;
    }
    
    public function index(){
        return view("reset.index");
    }
    
    public function resetDatabase(): RedirectResponse
    {
        $this->resetService->resetDatabase();
        Session::flash('success', 'Reinitialisation reussi');
        return redirect()->back();
    }
}
