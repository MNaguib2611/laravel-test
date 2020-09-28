<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\PostRequest;

use App\Jobs\ProfanityCheck;

class PostController extends Controller
{

    public function index(): JsonResponse
    {
        //used $with mutator for eager loading of user
       return  response()->json([
            'count' => Post::count(), 
            'posts' => PostResource::collection(Post::all()), 
       ],200);
    }





    public function store(PostRequest $request): JsonResponse
    {
        //create the (valid) post and attach it to the logged user
        $post = auth()->user()->posts()->create($request->all());
        
        // dispatch the post to the Post profanityCheck queue
        $postCheck = (new ProfanityCheck($post,auth()->user()));
        dispatch($postCheck);
        
        //return a response that the post was created successfull(this happens without waiting for the check)
        return response()->json(['message' => 'Post created Successfully.'], 202);
    }





    public function show(Post $post): JsonResource
    {
        //Lazy Eager Loading
        return new PostResource($post->load('comments'));
    }
}
