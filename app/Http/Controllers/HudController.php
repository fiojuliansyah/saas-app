<?php

namespace App\Http\Controllers;

use App\Models\Hud;
use Illuminate\Http\Request;

class HudController extends Controller
{
    public function index()
    {
        return view('huds.hud-controller-panel');
    }
}
