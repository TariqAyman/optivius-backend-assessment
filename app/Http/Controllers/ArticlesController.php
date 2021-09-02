<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Rules\LocalesInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(Request $request)
    {
        $articles = Article::query()->select(['id','title','content','created_at'])->where('user_id', $request->get('user_id'))->get();

        return $this->success($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $this->validator($request, [
            'title' => ['required', new LocalesInput()],
            'content' => ['required', new LocalesInput()],
        ]);

        $article = Article::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'user_id' => $request->get('user_id')
        ]);

        if ($article) return $this->success($article);

        return $this->badRequest('Can\'n Create  Article');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);

        return $this->success($article);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validator($request, [
            'title' => ['required', new LocalesInput()],
            'content' => ['required', new LocalesInput()],
        ]);

        $article = Article::find($id);

        if ($article) {

            $article->update([
                'title' => $request->get('title'),
                'content' => $request->get('content'),
            ]);

            return $this->success($article);
        }

        return $this->badRequest('Can\'n Update Article');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return string
     */
    public function destroy($id)
    {
        if ($article = Article::find($id)) {
            $article->delete();

            return $this->success('Delete Article Success');
        }

        return $this->notFound('Can\'t Delete Article');
    }
}
