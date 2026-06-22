<?php

namespace App\Http\Controllers\PanelControl;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;



class DashboardController extends Controller
{
    public function index()
    {
        return view('panel_control.dashboard', [
            'bookCount' => Book::count(),
            'userCount' => User::count(),
        ]);
    }
}
