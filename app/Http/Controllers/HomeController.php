<?php

namespace App\Http\Controllers;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
        {
            $user = Auth::user();
            if ($user->hasRole('super_admin')) {
                // Dashboard super admin
                return view('dashboard.super_admin', compact('user'));
            }
            // Dashboard abonné
            return view('abonne', compact('user'));
    }
    
}
