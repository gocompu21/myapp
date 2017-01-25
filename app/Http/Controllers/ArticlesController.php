<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ArticlesRequest;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $articles = \App\Article::with('user')->get();
//        $articles = \App\Article::get();
//        $articles->load('user');
        $articles = \App\Article::latest()->paginate(5);
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticlesRequest $request)
    {
//        $rules = [
//            'title' => ['required'],
//            'content' => ['required','min:10'],
//        ];
//
//        $message = [
//            'title.required' => '제목은 필수 입력 항목입니다',
//            'content.required' => '본문은 필수 입력 항목입니다',
//            'content.min' => '본문은 최소 :min 글자 이상 필요합니다.',
//        ];

//        $validator = \Validator::make($request->all(), $rules, $message);
//
//        if($validator->fails()){
//            return back()->withErrors($validator)->withInput();
//        }

//        $this->validate($request, $rules, $message);

        $article = \App\User::find(1)->articles()->create($request->all());

        if(! $article){
            return back()->with('flash_message','글이 저장되지 않았습니다')->withInput();
        }

        return redirect(route('articles.index'))->with('flash_message','작성하신 글이 저장되었습니다');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return __METHOD__ . '은 Article 컬렉션을 show' ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return __METHOD__ . '은 Article 컬렉션을 edit' ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return __METHOD__ . '은 Article 컬렉션을 update' ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return __METHOD__ . '은 Article 컬렉션을 destroy' ;
    }
}
