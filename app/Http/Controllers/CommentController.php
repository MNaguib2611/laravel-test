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
use App\Jobs\ProfanityCheckComment;


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

        //create a comment and attach it to auth user & post
        $comment = auth()->user()->comments()->make($request->all());
        $post = $post->comments()->save($comment);

          // dispatch the post to the Comment profanityCheck queue
        $commentCheck = (new ProfanityCheckComment($comment,auth()->user() ));
        dispatch($commentCheck);

      //return a response that the post was created successfull(this happens without waiting for the check)
        return response()->json(['message' => 'Comment created Successfully.'], 202);
    }
}
