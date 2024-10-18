<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Support\Facades\Request;

class AppController 
{   
    

    public function index(): Response
    {
        return inertia('Index');
    }

    public function loginForm(): Response
    {
        return inertia('Login');
    }

    public function admin(): Response
    {
        return inertia('Dashboard/Index');
    }


   
}
