<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Comment as CommentResource;


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
        return response()->json([
                            'message' => 'Success.',
                            'comment' =>new CommentResource($comment)
                                    ], 202);
    }
}
