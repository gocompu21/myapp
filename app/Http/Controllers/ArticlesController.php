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
    public function index(Request $request, $slug = null) {

        $cacheKey = cache_key('articles.index');

        $query = $slug
            ? \App\Tag::whereSlug($slug)->firstOrFail()->articles()
            : new \App\Article;

        $query = $query->orderBy(
            $request->input('sort', 'created_at'),
            $request->input('order', 'desc')
        );

        if ($keyword = request()->input('q')) {
            $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
            $query = $query->whereRaw($raw, [$keyword]);
        }

    //    $articles = $query->paginate(3);
        $articles = $this->cache($cacheKey, 5, $query, 'paginate', 3);

//        return view('articles.index', compact('articles'));
        return $this->respondCollection($articles);
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
        // 글 저장
        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);

        //$article = $request->user()->articles()->create($payload);
        $article = \App\User::find(1)->articles()->create($payload);

        if (! $article) {
            flash()->error('작성하신 글을 저장하지 못했습니다.');
            return back()->withInput();
        }

        // 태그 싱크
        $article->tags()->sync($request->input('tags'));

        event(new \App\Events\ArticlesEvent($article));
        event(new \App\Events\ModelChanged(['articles']));

        return $this->respondCreated($article);

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
        $article->view_count += 1;
        $article->save();

        $comments = $article->comments()
            ->with('replies')
            ->withTrashed()
            ->whereNull('parent_id')
            ->latest()->get();

        return $this->respondInstance($article, $comments);
 //       return view('articles.show', compact('article', 'comments'));
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

        //return redirect(route('articles.show', $article->id));
        return $this->respondUpdated($article);
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

    protected function respondCreated($article)
    {
        flash()->success(
            trans('forum.articles.success_writing')
        );

        return redirect(route('articles.show', $article->id));
    }

    protected function respondCollection(\Illuminate\Contracts\Pagination\LengthAwarePaginator $articles)
    {
        return view('articles.index', compact('articles'));
    }

    /**
     * @param \App\Article $article
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function respondInstance(\App\Article $article, \Illuminate\Database\Eloquent\Collection $comments)
    {
        return view('articles.show', compact('article', 'comments'));
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondUpdated(\App\Article $article)
    {
        flash()->success(trans('forum.articles.success_updating'));
        return redirect(route('articles.show', $article->id));
    }

}
