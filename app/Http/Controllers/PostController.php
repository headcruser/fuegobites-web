<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->select(['id', 'title', 'body'])
            ->when($request->input('search'), function ($q, $search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->paginate($request->input('perPage') ?? 10)
            ->withQueryString();

        return Inertia::render('Posts/Index', [
            'posts'     => $posts,
            'filters'   => $request->only(['search', 'perPage']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required',
            'body'  => 'required',
        ])->validate();

        Post::create($request->except('_token'));

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post
        ]);
    }

    public function update(Post $post, Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required',
            'body'  => 'required',
        ])->validate();

        $post->update($request->except('_token'));

        return redirect()->route('post.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('post.index');
    }
}
