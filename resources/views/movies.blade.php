<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Practice</title>
</head>
<body>
    <h1>映画一覧</h1>
    <!-- 検索フォーム -->
    <form action="{{ route('movies') }}" method="get">
        @csrf
        <input type="radio" name="is_showing" value="" id="all" checked>
        <label for="all">すべて</label>
        <input type="radio" name="is_showing" value="1" id="showing">
        <label for="showing">上映中</label>
        <input type="radio" name="is_showing" value="0" id="screeningSchedule">
        <label for="screeningSchedule">上映予定</label>
        <label for="keyword">キーワード：</label>
        <input type="text" name="keyword" id="keyword" value="">
        <input type="submit" value="検索">
    </form>
    <table>
        <tr>
            <th>タイトル</th>
            <th>画像</th>
            <th>公開年</th>
            <th>説明</th>
            <th>公開中かどうか</th>
        </tr>
        @foreach ($movies as $movie)   
        <tr>
            <td>{{ $movie->title }}</td>
            <td><img src="{{ $movie->image_url }}" alt=""></td>
            <td>{{ $movie->published_year }}</td>
            <td>{{ $movie->description }}</td>
            <td>{{ $movie->is_showing ? '上映中' : '上映予定' }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>