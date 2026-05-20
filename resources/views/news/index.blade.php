@extends('layouts.app')

@section('title', 'Новости сервера')

@section('header', '📢 НОВОСТИ СЕРВЕРА')

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>📰 Все новости</span>
            @auth
                <a href="{{ route('news.create') }}" class="btn">➕ ДОБАВИТЬ НОВОСТЬ</a>
            @endauth
        </div>
        <div class="card-body">
            @forelse($news as $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3>{{ $item->title }}</h3>
                        <p>{{ $item->description }}</p>
                        <small>📅 {{ $item->created_at->format('d.m.Y H:i') }}</small>

                        @auth
                            @if(Auth::user()->is_admin)
                                <div style="margin-top: 10px;">
                                    @if($item->status == 'pending')
                                        <span style="color: #ff9800;">⏳ На модерации</span>
                                    @elseif($item->status == 'rejected')
                                        <span style="color: #f44336;">❌ Отклонена</span>
                                        @if($item->moderation_comment)
                                            <p><small>Причина: {{ $item->moderation_comment }}</small></p>
                                        @endif
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('news.destroy', $item) }}" style="display: inline; margin-top: 10px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Удалить новость?')">🗑 УДАЛИТЬ</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            @empty
                <p>Новостей пока нет</p>
            @endforelse
            {{ $news->links() }}
        </div>
    </div>
@endsection
