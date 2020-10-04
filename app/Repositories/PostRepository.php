<?php

namespace App\Repositories;


use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\Post as PostResource;
use App\Jobs\ProfanityCheck;



class PostRepository{

  public function all()
  {
    return  [
      'count' => Post::count(), 
      'posts' => PostResource::collection(Post::all()), 
    ];
  }



  public function fetchById($id)
  {
    $post = Post::findOrFail($id);
    // Lazy Eager Loading
    return new PostResource($post->load('comments')->loadCount('comments'));
  }
 

  public function validatePost(Post $post)
  {
    $postCheck = (new ProfanityCheck($post,
                        "Post",
                        auth()->user()));
    dispatch($postCheck);
  }
  

  



}
