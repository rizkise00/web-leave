<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isManajer()) {
            return view('dashboard.manajer');
        }

        return view('dashboard.user');
    }
}
