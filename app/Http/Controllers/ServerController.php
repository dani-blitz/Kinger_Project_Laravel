<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::orderBy('order')->get();
        return view('servers.index', compact('servers'));
    }
}
