<?php

namespace App\Http\Controllers\PanelControl;

use App\Http\Controllers\Controller;



class DashboardController extends Controller
{
    public function index()
    {
        return view('panel_control.dashboard');
    }
}
