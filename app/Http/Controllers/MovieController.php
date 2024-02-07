<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

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
            //$request->is_showing = true;
        } else {
            $request->merge(['is_showing' => false]);
            //$request->is_showing = false;
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
            $validated = new Movie();
            $validated->title = $request->title;
            $validated->image_url = $request->image_url;
            $validated->published_year = $request->published_year;
            $validated->description = $request->description;
            $validated->is_showing = $request->is_showing;
            $validated->save();
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
}
