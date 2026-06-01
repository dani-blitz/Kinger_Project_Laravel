@extends('layouts.admin')

@section('title', 'Бан-лист слов')

@section('content')
    <div class="card">
        <div class="card-header">
            🚫 УПРАВЛЕНИЕ БАН-ЛИСТОМ (СТОП-СЛОВА)
            <small style="float:right; font-size:12px;">Эти слова будут исключены из статистики популярных слов в репортах</small>
        </div>
        <div class="card-body">
            <!-- Форма добавления -->
            <form method="POST" action="{{ route('admin.banned-words.store') }}" style="margin-bottom: 30px; display: flex; gap: 10px; align-items: flex-end;">
                @csrf
                <div style="flex:1">
                    <label>📝 Добавить слово в бан-лист</label>
                    <input type="text" name="word" required placeholder="например: читер, хакер, скилл" style="width:100%">
                </div>
                <div>
                    <button type="submit" class="btn">➕ Добавить</button>
                </div>
            </form>

            <!-- Список забаненных слов -->
            @if($bannedWords->count() > 0)
                <table class="table">
                    <thead>
                    <tr><th>ID</th><th>Слово</th><th>Тип</th><th>Дата добавления</th><th>Действия</th></tr>
                    </thead>
                    <tbody>
                    @foreach($bannedWords as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><code>{{ $item->word }}</code></td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.banned-words.destroy', $item->id) }}" onsubmit="return confirm('Удалить слово «{{ $item->word }}»?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $bannedWords->links() }}
            @else
                <p>В бан-листе пока нет слов. Добавьте первое слово.</p>
            @endif
        </div>
    </div>
@endsection
