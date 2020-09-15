<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Comment as CommentResource;
use App\Services\TextModerator;
use App\Notifications\CommentNotification;


class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CommentRequest $request, Post $post): JsonResponse
    {
        //if user tried to comment on an unapproved post
        if ($post->status !== Post::APPROVED) {
            abort(404, "No query results for model [App\\Models\\Post] ".$post->id);
        }

        $comment = auth()->user()->comments()->make($request->all());
        $post = $post->comments()->save($comment);
        // TODO: Perform text moderation, approve/reject the comment, send a notification to the user.
        
        $textmoderator = new TextModerator();            
        if($textmoderator->check($comment->content)){
            $comment->approve();
            auth()->user()->notify(new CommentNotification("your comment $comment->content was approved"));
        }else{
            $comment->reject();
            auth()->user()->notify(new CommentNotification("your comment $comment->content was rejected"));
        }


        return response()->json(['message' => 'Comment created Successfully.'], 202);
    }
}
