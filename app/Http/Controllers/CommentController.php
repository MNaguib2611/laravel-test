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

class CommentController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }


    public function store(CommentRequest $request, Post $post): JsonResponse
    {
        //create a comment and attach it to auth user & post
        $comment = $this->users->storeComment($request);
        $post = $post->comments()->save($comment);


        // dispatch the post to the Comment profanityCheck queue
        $this->backgroudCommentValidate($comment);

      //return a response that the post was created successfull(this happens without waiting for the check)
        return response()->json(['message' => 'Comment created Successfully.'], 202);
    }




  public function backgroudCommentValidate(Comment $comment){
      $commentCheck = (new ProfanityCheck($comment,
                            "Comment",  
                            auth()->user() ));
      dispatch($commentCheck);
  }
}
