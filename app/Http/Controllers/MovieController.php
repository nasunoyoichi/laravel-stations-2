<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MovieController extends Controller
{
    

    public function index(Request $request)
    {
        $query = Movie::query();
        if (!empty($request->input('keyword'))) {
            $spaceConversion = mb_convert_kana($request->input('keyword'), 's');
            $wordArraySearched = preg_split('/[\s]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($wordArraySearched as $keyword) {
                $query->where('title', 'like', '%'.$keyword.'%');
                $query->orWhere('description', 'like', '%'.$keyword.'%');
            }
        }
        $is_showing = $request->input('is_showing');
        $keyword = $request->input('keyword');
        if ($is_showing === '1') {
            $query->where('is_showing', true);
        } else if ($is_showing === '0') {
            $query->where('is_showing', false);
        } else ;
        $movies = $query->paginate(20);
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
        //dd($movie_array);
        //dd($request);
        $request->validate([
            'title' => 'required|unique:movies,title',
            'image_url' => 'required|url',
            'published_year' => 'required|integer',
            'description' => 'required',
            'genre' => 'required',
            'is_showing' => 'required'
        ],[
            'title.required' => 'タイトルは必須です。',
            'title.unique' => 'そのタイトルは既に登録されています。',
            'image_url.required' => '画像URLは必須です。',
            'image_url.url' => 'URLを正しく入力してください。',
            'published_year.required' => '公開年は必須です。',
            'published_year.integer' => '公開年は整数で入力してください。',
            'description.required' => '説明は必須です。',
            'genre.required' => 'ジャンルは必須です。',
            'is_showing.required' => '公開中かどうかは必須です。'
        ]);

        $movie_array = $request->all();

        if ($movie_array['is_showing'] === 'on') {
            $movie_array['is_showing'] = true;
        }
        //dd($movie_array);
        try {
            DB::transaction(function () use ($movie_array){
                $genreName = $movie_array['genre'];
                //dd($genreName);
                $genre = Genre::where('name', $genreName)->get();
                //dd($genre);
                if (count($genre) == 0) {
                    $genre = new Genre();
                    $genre->name = $movie_array['genre'];
                    $genre->save();
                }
                $genreID = Genre::where('name', $genreName)->first()->id;
                //dd($genreID);

                $movie = new Movie();
                $movie->title = $movie_array['title'];
                $movie->image_url = $movie_array['image_url'];
                $movie->published_year = $movie_array['published_year'];
                $movie->description = $movie_array['description'];
                $movie->genre_id = $genreID;
                $movie->is_showing = $movie_array['is_showing'];
                $movie->save();
            });
            return redirect('admin/movies/create')->with('message', '登録が完了しました。');
        } catch (\Exception $e) {
            return redirect('/admin/movies/create')->with('message', $e);
        }
        
    }

    public function edit($id)
    {
        $movie = Movie::find($id);
        $genreName = Genre::find($movie->genre_id)->name;
        return view('moviesEditForm', ['movie' => $movie, 'genreName' => $genreName]);
    }

    public function update(Request $request)
    {
        //dd($request);
        if ($request->is_showing === 'on') {
            $request->merge(['is_showing' => true]);
        }
        //dd($request->is_showing);

        $request->validate([
            'title' => ['required', Rule::unique('movies')->ignore($request->id)],
            'image_url' => 'required|url',
            'published_year' => 'required|integer',
            'description' => 'required',
            'genre' => 'required',
            'is_showing' => 'required'
        ],[
            'title.required' => 'タイトルは必須です。',
            'title.unique' => 'そのタイトルは既に登録されています。',
            'image_url.required' => '画像URLは必須です。',
            'image_url.url' => 'URLを正しく入力してください。',
            'published_year.required' => '公開年は必須です。',
            'published_year.integer' => '公開年は整数で入力してください。',
            'description.required' => '説明は必須です。',
            'genre.required' => 'ジャンルは必須です。',
            'is_showing.required' => '公開中かどうかは必須です。'
        ]);

        try {
            DB::transaction(function () use ($request){
                $genreName = $request->input('genre');
                //dd($genreName);
                $genre = Genre::where('name', $genreName)->get();
                //dd($genre);
                if (count($genre) == 0) {
                    $genre = new Genre();
                    $genre->name = $request->genre;
                    $genre->save();
                }
                $genreID = Genre::where('name', $genreName)->first()->id;

                $movie = new Movie();
                $movie->title = $request->title;
                $movie->image_url = $request->image_url;
                $movie->published_year = $request->published_year;
                $movie->description = $request->description;
                $movie->genre_id = $genreID;
                $movie->is_showing = $request->is_showing;
                $movie->save();
            });
            return redirect('/admin/movies/'.$request->id.'/edit')->with('message', '更新が完了しました。');
            
        } catch (\Exception $e) {
            return redirect('/admin/movies/'.$request->id.'/edit')->with('message', $e);
        }
    }

    public function destroy($id)
    {
        //idが一致するレコードを削除、削除対象がない場合は404エラーを返す
        $movie = Movie::find($id);
        if ($movie) {
            $movie->delete();
            return redirect('/admin/movies',302)->with('message', '削除が完了しました。');
        } else {
            return abort(404);
        }
    }
}
