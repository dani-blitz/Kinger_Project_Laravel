<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannedWord;
use Illuminate\Http\Request;

class BannedWordsController extends Controller
{
    public function index()
    {
        $bannedWords = BannedWord::orderBy('word')->paginate(20);
        return view('admin.banned-words', compact('bannedWords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:50|unique:banned_words,word',
            'type' => 'in:stop'
        ]);

        BannedWord::create([
            'word' => mb_strtolower($request->word),
            'type' => $request->type ?? 'stop'
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
