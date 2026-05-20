<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    // Показываем только опубликованные новости для всех
    public function index()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            // Админы видят все новости
            $news = News::orderBy('id', 'desc')->paginate(10);
        } else {
            // Обычные пользователи видят только опубликованные
            $news = News::where('status', 'approved')->orderBy('id', 'desc')->paginate(10);
        }
        return view('news.index', compact('news'));
    }

    // Форма создания новости (доступна всем)
    public function create()
    {
        return view('news.create');
    }

    // Сохранение новости (статус pending для обычных, approved для админов)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $status = Auth::user()->is_admin ? 'approved' : 'pending';

        News::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'user_id' => auth()->id(),
            'status' => $status,
        ]);

        $message = Auth::user()->is_admin
            ? 'Новость опубликована'
            : 'Новость отправлена на модерацию';

        return redirect()->route('news.index')->with('success', $message);
    }

    // Просмотр новости
    public function show(News $news)
    {
        // Проверяем, может ли пользователь видеть новость
        if ($news->status !== 'approved' && !(Auth::check() && Auth::user()->is_admin)) {
            abort(404);
        }
        return view('news.show', compact('news'));
    }

    // Редактирование (только для админов)
    public function edit(News $news)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }
        return view('news.edit', compact('news'));
    }

    // Обновление (только для админов)
    public function update(Request $request, News $news)
    {
        if (!Auth::user()->is_admin) {
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

    // Удаление (только для админов)
    public function destroy(News $news)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }
        $news->delete();
        return redirect()->route('news.index')->with('success', 'Новость удалена');
    }

    // ========== АДМИНСКИЕ МЕТОДЫ ==========

    // Показать новости на модерации
    public function pending()
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }
        $pendingNews = News::where('status', 'pending')->orderBy('id', 'desc')->get();
        return view('admin.pending-news', compact('pendingNews'));
    }

    // Одобрить новость
    public function approve($id)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $news = News::findOrFail($id);
        $news->status = 'approved';
        $news->moderated_by = auth()->id();
        $news->moderated_at = now();
        $news->save();

        return redirect()->back()->with('success', 'Новость одобрена');
    }

    // Отклонить новость
    public function reject(Request $request, $id)
    {
        if (!Auth::user()->is_admin) {
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
