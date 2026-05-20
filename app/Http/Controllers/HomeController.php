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
        $reports = Report::where('user_id', auth()->id())->orderBy('id', 'desc')->limit(5)->get();
        return view('home', compact('news', 'reports'));
    }
}
