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
        //only approved posts
        $posts = PostResource::collection(Post::approvedPosts()->get());       


        //Add posts count to response.
        // only approved posts & approved comments
       return  response()->json([
            'count' => count($posts), 
            'posts' => PostResource::collection($posts),
       ],200);
    }





    public function store(PostRequest $request): JsonResponse
    {
        //create the (valid) post and attach it to the logged user
        $post = auth()->user()->posts()->create($request->all());
        
        // dispatch the post to the Post profanityCheck queue
        $postCheck = (new ProfanityCheck($post,auth()->user() ));
        dispatch($postCheck);
        
        //return a response that the post was created successfull(this happens without waiting for the check)
        return response()->json(['message' => 'Post created Successfully.'], 202);
    }





    public function show(Post $post)
    {
        //show only approved posts
        if ($post->status == Post::APPROVED) {
            return new PostResource($post);
        }
        //incase the post hasn't been approved
        abort(404, "No query results for model [App\\Models\\Post] ".$post->id);
    }
}
