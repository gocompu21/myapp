<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ArticlesRequest;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' =>['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug = null)
    {
//        $articles = \App\Article::with('user')->get();
//        $articles = \App\Article::get();
//        $articles->load('user');
        $query = $slug
            ? \App\Tag::whereSlug($slug)->firstOrFail()->articles()
            : new \App\Article;

        $articles = $query->latest()->paginate(5);
        //dd(view('articles.index', compact('articles'))->render());
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new \App\Article;
        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticlesRequest $request)
    {
        //$article = \App\User::find(1)->articles()->create($request->all());
        $article = $request->user()->articles()->create($request->all());
        if(! $article){
            return back()->with('flash_message','글이 저장되지 않았습니다')->withInput();
        }
        $article->tags()->sync($request->input('tags'));

//        if ($request->hasFile('files')) {
//            // 파일 저장
//            $files = $request->file('files');
//
//            foreach($files as $file) {
//                $filename = str_random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
//
//                // 순서 중요 !!!
//                // 파일이 PHP의 임시 저장소에 있을 때만 getSize, getClientMimeType등이 동작하므로,
//                // 우리 프로젝트의 파일 저장소로 업로드를 옮기기 전에 필요한 값을 취해야 함.
//                $article->attachments()->create([
//                    'filename' => $filename,
//                    'bytes' => $file->getSize(),
//                    'mime' => $file->getClientMimeType()
//                ]);
//
//                $file->move(attachments_path(), $filename);
//            }
//        }

        event(new \App\Events\ArticlesEvent($article));
        flash()->success('작성하신 글이 저장되었습니다.');
        return redirect(route('articles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Article $article)
    {
//        $article = \App\Article::findOrFail($id);
        return view('articles.show',compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Article $article)
    {
        $this->authorize('update', $article);
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \App\Article $article)
    {
        $this->authorize('update', $article);
        $article->update($request->all());
        $article->tags()->sync($request->input('tags'));
  //      dd($request);
        flash()->success('수정하신 내용을 저장했습니다.');
        return redirect(route('articles.show', $article->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();
        return response()->json([], 204);
    }
}
