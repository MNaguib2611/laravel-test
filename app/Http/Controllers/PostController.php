<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{

    public function index(): JsonResponse
    {
        //only approved posts
        $posts = PostResource::collection(Post::approvedPosts()->get());       


        // TODO: Add posts count to response.
       return  response()->json([
            'count' => count($posts), 
            'posts' => PostResource::collection($posts),
       ],200);


    }





    public function store(PostRequest $request): JsonResponse
    {
        //create the (valid) post and attach it to the logged user
            $post = auth()->user()->posts()->create($request->all());
            
            // TODO: Perform text moderation, approve/reject the post, send a notification to the user.
            


            return response()->json(['message' => 'Success.'], 202);
    }





    public function show(Post $post)
    {
        // TODO: Refactor (N+1).
        if ($post->status == Post::APPROVED) {
            return new PostResource($post);
        }
        //incase the post hasn't been approved
        abort(404, "No query results for model [App\\Models\\Post] ".$post->id);
    }
}
