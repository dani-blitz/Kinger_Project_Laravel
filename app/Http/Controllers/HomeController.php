<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $news = News::orderBy('id', 'desc')->limit(5)->get();

        // Репорты видят только модераторы и выше
        $reports = [];
        if (auth()->check() && auth()->user()->isModerator()) {
            $reports = Report::orderBy('id', 'desc')->limit(5)->get();
        }

        return view('home', compact('news', 'reports'));
    }
}
