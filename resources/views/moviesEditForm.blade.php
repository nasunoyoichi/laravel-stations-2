<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>映画登録</title>
</head>
<body>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif 
    @if(session('message'))
	<div>
		{{ session('message') }}
	</div>
    @endif

    <h1>moviesテーブルのデータ編集</h1>
    <form action="/admin/movies/{id}/update" method="POST">
    @method('patch')
    @csrf
        <div>
            <label for="title">タイトル</label>
            <input type="text" name="title" id="title" value="{{ $movie->title }}">
        </div>
        <div>
            <label for="image_url">画像URL</label>
            <input type="text" name="image_url" id="image_url" value="{{ $movie->image_url }}">
        </div>
        <div>
            <label for="published_year">公開年</label>
            <input type="text" name="published_year" id="published_year" value="{{ $movie->published_year }}">
        </div>
        <div>
            <label for="description">説明</label>
            <textarea name="description" id="description" cols="30" rows="10">{{ $movie->description }}</textarea>
        </div>
        <div>
            <label for="genre">ジャンル</label>
            <textarea name="genre" id="genre">{{ $genreName }}</textarea>
        </div>
        <div>
            <label for="is_showing">公開中かどうか</label>
            <input type="checkbox" name="is_showing" id="is_showing" {{ $movie->is_showing ? 'checked' : '' }}>
        </div>
        <div>
            <input type="hidden" name="id" value="{{ $movie->id }}">
        </div>
        <button type="submit">更新</button>
</body>
</html>