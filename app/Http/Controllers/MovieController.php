<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MovieController extends Controller
{
    

    public function index()
    {
        $movies = Movie::all();
        return view('movies', ['movies' => $movies]);
    }

    public function adminIndex()
    {
        $movies = Movie::all();
        return view('moviesManage', ['movies' => $movies]);
    }

    public function create()
    {
        return view('moviesCreateForm');
    }

    public function store(Request $request)
    {
        // dd($request->is_showing);
        if ($request->is_showing === 'on') {
            $request->merge(['is_showing' => true]);
        }
        // dd($request);

        $request->validate([
            'title' => 'required|unique:movies,title',
            'image_url' => 'required|url',
            'published_year' => 'required|integer',
            'description' => 'required',
            'is_showing' => 'required'
        ],[
            'title.required' => 'タイトルは必須です。',
            'title.unique' => 'そのタイトルは既に登録されています。',
            'image_url.required' => '画像URLは必須です。',
            'image_url.url' => 'URLを正しく入力してください。',
            'published_year.required' => '公開年は必須です。',
            'published_year.integer' => '公開年は整数で入力してください。',
            'description.required' => '説明は必須です。',
            'is_showing.required' => '公開中かどうかは必須です。'
        ]);

        // try catchでエラー処理を行う
        try {
            $movie = new Movie();
            $movie->title = $request->title;
            $movie->image_url = $request->image_url;
            $movie->published_year = $request->published_year;
            $movie->description = $request->description;
            $movie->is_showing = $request->is_showing;
            $movie->save();
            return redirect('/admin/movies/create')->with('flash_message', '登録が完了しました。');
        } catch (\Exception $e) {
            return redirect('/admin/movies/create')->with('flash_message', '登録に失敗しました。');
        }
    }

    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('moviesEditForm', ['movie' => $movie]);
    }

    public function update(Request $request)
    {
        //dd($request);
        if ($request->is_showing === 'on') {
            $request->merge(['is_showing' => true]);
        }
        $request->validate([
            'title' => ['required', Rule::unique('movies')->ignore($request->id)],
            'image_url' => 'required|url',
            'published_year' => 'required|integer',
            'description' => 'required',
            'is_showing' => 'required'
        ],[
            'title.required' => 'タイトルは必須です。',
            'title.unique' => 'そのタイトルは既に登録されています。',
            'image_url.required' => '画像URLは必須です。',
            'image_url.url' => 'URLを正しく入力してください。',
            'published_year.required' => '公開年は必須です。',
            'published_year.integer' => '公開年は整数で入力してください。',
            'description.required' => '説明は必須です。',
            'is_showing.required' => '公開中かどうかは必須です。'
        ]);

        //dd($request->all());
        try {
            $movie = Movie::find($request->id);
            $movie->title = $request->title;
            $movie->image_url = $request->image_url;
            $movie->published_year = $request->published_year;
            $movie->description = $request->description;
            $movie->is_showing = $request->is_showing;
            $movie->save();
            return redirect('/admin/movies/'.$request->id.'/edit',302);
        } catch (\Exception $e) {
            return redirect('/admin/movies/'.$request->id.'/edit',302);
        }
    }

    public function destroy($id)
    {
        //idが一致するレコードを削除、削除対象がない場合は404エラーを返す
        $movie = Movie::find($id);
        if ($movie) {
            $movie->delete();
            return redirect('/admin/movies',302)->with('flash_message', '削除が完了しました。');
        } else {
            return abort(404);
        }
    }
}
