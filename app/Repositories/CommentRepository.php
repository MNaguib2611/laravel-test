<?php

namespace App\Repositories;


use Illuminate\Http\Request;
use App\Models\Comment;
use App\Jobs\ProfanityCheck;


class CommentRepository{


  public function validateComment(Comment $comment){
    $commentCheck = (new ProfanityCheck($comment,
                          "Comment",  
                          auth()->user() ));
    dispatch($commentCheck);
}

  



}
