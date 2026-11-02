<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MainDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('main-dashboard', [
            'user' => $user,
            'branch' => $user->branch ?? $user->employee?->branch,
        ]);
    }
}
