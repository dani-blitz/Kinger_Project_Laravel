<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\BannedWord;
use Illuminate\Http\Request;

class BannedWordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('moderator');
    }

    public function index()
    {
        $bannedWords = BannedWord::orderBy('word')->paginate(20);
        return view('moderator.banned-words', compact('bannedWords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:50|unique:banned_words,word',
        ]);

        BannedWord::create([
            'word' => mb_strtolower($request->word),
            'type' => 'stop'
        ]);

        return redirect()->back()->with('success', "Слово «{$request->word}» добавлено в бан-лист");
    }

    public function destroy($id)
    {
        $bannedWord = BannedWord::findOrFail($id);
        $word = $bannedWord->word;
        $bannedWord->delete();
        return redirect()->back()->with('success', "Слово «{$word}» удалено из бан-листа");
    }
}
