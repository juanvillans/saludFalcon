<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WaitingRoomController extends Controller
{
    public function index(){
       return inertia('WaitingRoom');
    }
}
