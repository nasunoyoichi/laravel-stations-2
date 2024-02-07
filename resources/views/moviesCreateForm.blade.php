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

    <h1>moviesテーブルにデータ登録</h1>
    <form action="/admin/movies/store" method="POST">
    @csrf
        <div>
            <label for="title">タイトル</label>
            <input type="text" name="title" id="title">
        </div>
        <div>
            <label for="image_url">画像URL</label>
            <input type="text" name="image_url" id="image_url">
        </div>
        <div>
            <label for="published_year">公開年</label>
            <input type="number" name="published_year" id="published_year">
        </div>
        <div>
            <label for="description">説明</label>
            <textarea name="description" id="description"></textarea>
        </div>
        <div>
            <label for="is_showing">公開中かどうか</label>
            <input type="hidden" name="is_showing" id="is_showing" value="off">
            <input type="checkbox" name="is_showing" id="is_showing">
        </div>
        <button type="submit">登録</button>
</body>
</html>