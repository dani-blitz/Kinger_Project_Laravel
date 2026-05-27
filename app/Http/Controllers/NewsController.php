<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            $news = News::orderBy('id', 'desc')->paginate(10);
        } else {
            $news = News::where('status', 'approved')->orderBy('id', 'desc')->paginate(10);
        }
        return view('news.index', compact('news'));
    }

    public function create()
    {
        // Все авторизованные пользователи могут предлагать новости
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Только администраторы (admin и super_admin) могут публиковать сразу
        $status = Auth::user()->isAdmin() ? 'approved' : 'pending';

        News::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'user_id' => auth()->id(),
            'status' => $status,
        ]);

        $message = Auth::user()->isAdmin()
            ? 'Новость опубликована'
            : 'Новость отправлена на модерацию';

        return redirect()->route('news.index')->with('success', $message);
    }

    public function show(News $news)
    {
        if ($news->status !== 'approved' && !Auth::user()?->isAdmin()) {
            abort(404);
        }
        return view('news.show', compact('news'));
    }

    public function edit(News $news)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $news->update($request->all());
        return redirect()->route('news.index')->with('success', 'Новость обновлена');
    }

    public function destroy(News $news)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $news->delete();
        return redirect()->route('news.index')->with('success', 'Новость удалена');
    }

    // ========== АДМИНСКИЕ МЕТОДЫ ==========

    public function pending()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $pendingNews = News::where('status', 'pending')->orderBy('id', 'desc')->get();
        return view('admin.pending-news', compact('pendingNews'));
    }

    public function approve($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $news = News::findOrFail($id);
        $news->status = 'approved';
        $news->moderated_by = auth()->id();
        $news->moderated_at = now();
        $news->save();

        return redirect()->back()->with('success', 'Новость одобрена');
    }

    public function reject(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $news = News::findOrFail($id);
        $news->status = 'rejected';
        $news->moderated_by = auth()->id();
        $news->moderated_at = now();
        $news->moderation_comment = $request->comment;
        $news->save();

        return redirect()->back()->with('success', 'Новость отклонена');
    }
}
