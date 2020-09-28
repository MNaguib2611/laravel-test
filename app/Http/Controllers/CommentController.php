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
use App\Jobs\ProfanityCheck;


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
        //create a comment and attach it to auth user & post
        $comment = auth()->user()->comments()->make($request->all());
        $post = $post->comments()->save($comment);

          // dispatch the post to the Comment profanityCheck queue
        $commentCheck = (new ProfanityCheck($comment,
                                          "Comment",  
                                          auth()->user() ));
        dispatch($commentCheck);

      //return a response that the post was created successfull(this happens without waiting for the check)
        return response()->json(['message' => 'Comment created Successfully.'], 202);
    }
}
