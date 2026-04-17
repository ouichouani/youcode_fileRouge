<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $post = Post::findOrFail($request->post_id) ;
        $this->authorize('create' , [Comment::class , $post]) ;

        $validated = $request->validated() ;
        $comment = Comment::create($validated) ;
        return redirect()->route('posts.show' , $comment->post_id)->with('message' , 'comment added successfully') ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete() ;
        return redirect()->route('posts.show' , $comment->post_id)->with('message' , 'comment deleted successfully') ;
    }
}
