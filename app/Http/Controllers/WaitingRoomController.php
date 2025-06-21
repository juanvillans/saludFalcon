<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class WaitingRoomController extends Controller
{
    public function index(){
       return inertia('WaitingRoom');
    }
}
