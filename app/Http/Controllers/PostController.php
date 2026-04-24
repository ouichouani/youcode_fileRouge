<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::query()->with(['comments.user.image' , 'comments.post', 'likes', 'user.image', 'images', 'reports' => function ($q) {
            $q->where('user_id', Auth::id());
        }])->latest()->get();

        return view('posts.index', compact('posts'));
    }


    public function show(Post $post)
    {
        $post->load(['comments.user.image:id,path,id', 'likes', 'user.image:id,path,id', 'images:path']);
        $comments = $post->comments;
        $likes = $post->likes;

        return view('posts.show', compact('post', 'comments', 'likes'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {

        $user = Auth::user();
        $data = $request->validated();

        $post = Post::create([
            'content' => $data['content'],
            'type' => $data['type'],
            'visibility' => $data['visibility'],
            'user_id' => $user->id,
        ]);

        if(isset($data['images'])) Image::storeMultiple($post, 'posts', $data['images']);
        

        return redirect()->route('posts.show', $post->id)->with('message', 'Post created successfully');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $data = $request->validated();
        $post->update($data);
        if(isset($data['images'])) Image::storeMultiple($post, 'posts', $data['images']);

        return redirect()->route('posts.show', $post)->with('message', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('posts.index')->with('message', 'Post deleted successfully');
    }
}
