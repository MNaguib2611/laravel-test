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
use App\Repositories\UserRepository;
use App\Repositories\CommentRepository;



class CommentController extends Controller
{
    protected $users;
    protected $comments;


    public function __construct(UserRepository $users,CommentRepository $comments)
    {
        $this->users = $users;
        $this->comments = $comments;

    }


    public function store(CommentRequest $request, Post $post): JsonResponse
    {
        //create a comment and attach it to auth user & post
        $comment = $this->users->makeComment($request);
        $post = $post->comments()->save($comment);


        // dispatch the post to the Comment profanityCheck queue
        $this->comments->validateComment($comment);

      //return a response that the post was created successfull(this happens without waiting for the check)
        return response()->json(['message' => 'Comment created Successfully.'], 202);
    }





}
