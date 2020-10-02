<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\PostRequest;
use App\Repositories\UserRepository;
use App\Repositories\PostRepository;


class PostController extends Controller
{



    protected $users;
    protected $posts;


    public function __construct(UserRepository $users,PostRepository $posts)
    {
        $this->users = $users;
        $this->posts = $posts;
    }





    public function index(): JsonResponse
    {
        //using UserRepository all() method
       return  response()->json($this->posts->all() ,200);
    }




    public function store(PostRequest $request): JsonResponse
    {
        //create the post
        $post = $this->users->storePost($request);
        
        // using  UserRepository to dispatch the post to the Post profanityCheck queue
       $this->posts->validatePost($post);
        
        //return a response that the post was created successfull(this happens without waiting for the check)
        return response()->json(['message' => 'Post created Successfully.'], 202);
    }





    public function show(Post $post): JsonResource
    {
        //Lazy Eager Loading
        return new PostResource($post->load('comments'));
    }



  



}
