<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        // TODO: Refactor.
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->passes()) {
            $comment = auth()->user()->comments()->make($validator->validated());
            $post = $post->comments()->save($comment);
            // TODO: Perform text moderation, approve/reject the comment, send a notification to the user.
            return response()->json(['message' => 'Success.'], 202);
        } else {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);
        }
    }
}
