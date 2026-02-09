<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    // Example method to show the dashboard or home page
    public function index()
    {
        return redirect('/admin');
    }
}
