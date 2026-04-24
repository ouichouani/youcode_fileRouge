<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {

        $post = Post::findOrFail($request->post_id) ;
        $this->authorize('create' , [Comment::class , $post]) ;
        $data = $request->validated() ;
        $data['user_id'] = Auth::id() ;
        Comment::create($data) ;
        return redirect()->back() ;
        // return redirect()->route('posts.show' , $comment->post_id)->with('message' , 'comment added successfully') ;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete() ;
        return redirect()->back() ;
        // return redirect()->route('posts.show' , $comment->post_id)->with('message' , 'comment deleted successfully') ;
    }
}
